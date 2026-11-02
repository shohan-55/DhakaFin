<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Services\CmsManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EnquiryController extends Controller
{
    public function __construct(protected CmsManager $cms)
    {
    }

    /**
     * Store a contact or consultation enquiry in both CMS store (for Admin Messages inbox) and DB.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $enquiries = $this->cms->get('enquiries', []);
        $nextId = collect($enquiries)->max('id') + 1;

        $entry = [
            'id' => $nextId,
            'type' => $validated['type'] ?? 'contact',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'organisation' => $validated['organisation'] ?? '',
            'service' => $validated['service'] ?? 'General Advisory',
            'preferred_date' => $validated['date'] ?? '',
            'message' => $validated['message'],
            'status' => 'Unread',
            'created_at' => now()->format('Y-m-d H:i'),
        ];

        array_unshift($enquiries, $entry);
        $this->cms->put('enquiries', $enquiries);

        try {
            Enquiry::create([
                'type' => $entry['type'],
                'name' => $entry['name'],
                'email' => $entry['email'],
                'organisation' => $entry['organisation'],
                'service' => $entry['service'],
                'preferred_date' => $entry['preferred_date'],
                'message' => $entry['message'],
            ]);
        } catch (Throwable $e) {
            // Handled via CmsManager JSON persistence
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Thank you, ' . $entry['name'] . '. Your enquiry (#' . $entry['id'] . ') has been received by DhakaFin and logged in our advisory inbox.',
                'entry' => $entry,
            ]);
        }

        return back()->with('status', 'Thank you! Your enquiry has been received by DhakaFin.');
    }
}
