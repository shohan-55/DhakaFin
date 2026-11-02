<?php

namespace App\Http\Controllers;

use App\Services\CmsManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct(protected CmsManager $cms)
    {
    }

    /**
     * Save global site settings (admin-settings).
     */
    public function saveSettings(Request $request): RedirectResponse
    {
        $settings = $this->cms->get('settings', []);
        foreach ([
            'site_name', 'tagline', 'email', 'phone', 'address',
            'theme_color', 'header_client_label', 'header_talk_label', 'meta_description'
        ] as $field) {
            if ($request->has($field)) {
                $settings[$field] = (string) $request->input($field, '');
            }
        }
        $settings['live_edit_toolbar'] = $request->boolean('live_edit_toolbar', true);
        $this->cms->put('settings', $settings);

        return back()->with('status', 'Site settings updated successfully! Changes are live across DhakaFin.');
    }

    /**
     * Save homepage hero & section content (admin-homepage).
     */
    public function saveHomepage(Request $request): RedirectResponse
    {
        $homepage = $this->cms->get('homepage', []);
        foreach ([
            'eyebrow', 'hero_title_line1', 'hero_title_serif', 'hero_lead',
            'cta_primary_text', 'cta_secondary_text', 'advisory_kicker',
            'advisory_title', 'tools_kicker', 'tools_title',
            'cta_banner_title', 'cta_banner_sub'
        ] as $field) {
            if ($request->has($field)) {
                $homepage[$field] = (string) $request->input($field, '');
            }
        }
        $this->cms->put('homepage', $homepage);

        // Also apply to homepage page_overrides replacements so any text in home.blade.php updates seamlessly
        $defaults = $this->cms->defaults()['homepage'];
        $overrides = $this->cms->get('page_overrides', []);
        $homeReplacements = $overrides['home']['replacements'] ?? [];

        $map = [
            'hero_lead' => $defaults['hero_lead'],
            'cta_primary_text' => $defaults['cta_primary_text'],
            'cta_secondary_text' => $defaults['cta_secondary_text'],
        ];
        foreach ($map as $key => $origText) {
            if (!empty($homepage[$key]) && $homepage[$key] !== $origText) {
                $homeReplacements[$key] = [
                    'find' => $origText,
                    'replace' => $homepage[$key],
                ];
            }
        }
        $overrides['home']['replacements'] = array_values($homeReplacements);
        $this->cms->put('page_overrides', $overrides);

        return back()->with('status', 'Homepage content saved and published live!');
    }

    /**
     * Save footer settings (admin-footer).
     */
    public function saveFooter(Request $request): RedirectResponse
    {
        $footer = $this->cms->get('footer', []);
        foreach ([
            'heading_line1', 'heading_serif', 'description',
            'home_description', 'copyright', 'support_note'
        ] as $field) {
            if ($request->has($field)) {
                $footer[$field] = (string) $request->input($field, '');
            }
        }
        $this->cms->put('footer', $footer);

        return back()->with('status', 'Footer content updated across all pages!');
    }

    /**
     * Save page-specific overrides or visual inline text replacements (admin-settings / live editor).
     */
    public function savePageOverride(Request $request): JsonResponse|RedirectResponse
    {
        $slug = (string) $request->input('slug', 'home');
        $overrides = $this->cms->get('page_overrides', []);
        $current = $overrides[$slug] ?? ['replacements' => []];

        if ($request->filled('hero_title')) {
            $current['hero_title'] = (string) $request->input('hero_title');
        }
        if ($request->filled('hero_lead')) {
            $current['hero_lead'] = (string) $request->input('hero_lead');
        }

        if ($request->filled('find_text') && $request->has('replace_text')) {
            $current['replacements'][] = [
                'find' => (string) $request->input('find_text'),
                'replace' => (string) $request->input('replace_text'),
            ];
        }

        if ($request->has('replacements') && is_array($request->input('replacements'))) {
            foreach ($request->input('replacements') as $pair) {
                if (!empty($pair['find']) && isset($pair['replace'])) {
                    $current['replacements'][] = [
                        'find' => (string) $pair['find'],
                        'replace' => (string) $pair['replace'],
                    ];
                }
            }
        }

        $overrides[$slug] = $current;
        $this->cms->put('page_overrides', $overrides);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => "Changes saved to '{$slug}' page!",
            ]);
        }

        return back()->with('status', "Page content override saved for '{$slug}'!");
    }

    /**
     * Create or update a blog article (admin-blog).
     */
    public function saveArticle(Request $request): RedirectResponse
    {
        $articles = $this->cms->get('articles', []);
        $id = (int) $request->input('id', 0);
        $title = (string) $request->input('title', 'Untitled Article');

        $item = [
            'id' => $id > 0 ? $id : (collect($articles)->max('id') + 1),
            'slug' => Str::slug($title),
            'code' => str_pad((string) ($id > 0 ? $id : (count($articles) + 1)), 2, '0', STR_PAD_LEFT),
            'art_class' => (string) $request->input('art_class', 'art-' . (count($articles) % 4)),
            'category' => strtoupper((string) $request->input('category', 'TAX & COMPLIANCE')),
            'title' => $title,
            'summary' => (string) $request->input('summary', ''),
            'subtitle' => (string) $request->input('subtitle', 'DhakaFin editorial layout · Insights'),
            'body_p1' => (string) $request->input('body_p1', ''),
            'body_h1' => (string) $request->input('body_h1', 'Key Considerations'),
            'body_p2' => (string) $request->input('body_p2', ''),
            'body_h2' => (string) $request->input('body_h2', 'Advisory Perspective'),
            'body_p3' => (string) $request->input('body_p3', ''),
            'status' => (string) $request->input('status', 'Published'),
            'date' => (string) ($request->input('date') ?: date('Y-m-d')),
        ];

        $updated = false;
        foreach ($articles as $idx => $existing) {
            if ((int) ($existing['id'] ?? 0) === $id && $id > 0) {
                $articles[$idx] = array_merge($existing, $item);
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            array_unshift($articles, $item);
        }

        $this->cms->put('articles', array_values($articles));
        return back()->with('status', 'Blog article saved and published to Insights!');
    }

    public function deleteArticle(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $articles = array_values(array_filter(
            $this->cms->get('articles', []),
            fn ($a) => (int) ($a['id'] ?? 0) !== $id
        ));
        $this->cms->put('articles', $articles);
        return back()->with('status', 'Article removed.');
    }

    /**
     * Create or update a tax deadline (admin-deadlines).
     */
    public function saveDeadline(Request $request): RedirectResponse
    {
        $deadlines = $this->cms->get('deadlines', []);
        $id = (int) $request->input('id', 0);

        $item = [
            'id' => $id > 0 ? $id : (collect($deadlines)->max('id') + 1),
            'year' => (string) $request->input('year', '2026–2027'),
            'category' => (string) $request->input('category', 'Personal tax'),
            'title' => (string) $request->input('title', 'Tax Compliance Filing'),
            'due_date' => (string) $request->input('due_date', date('Y-m-d')),
            'notes' => (string) $request->input('notes', ''),
            'status' => (string) $request->input('status', 'Upcoming'),
        ];

        $updated = false;
        foreach ($deadlines as $idx => $existing) {
            if ((int) ($existing['id'] ?? 0) === $id && $id > 0) {
                $deadlines[$idx] = $item;
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            array_unshift($deadlines, $item);
        }

        $this->cms->put('deadlines', array_values($deadlines));
        return back()->with('status', 'Compliance deadline saved!');
    }

    public function deleteDeadline(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $deadlines = array_values(array_filter(
            $this->cms->get('deadlines', []),
            fn ($d) => (int) ($d['id'] ?? 0) !== $id
        ));
        $this->cms->put('deadlines', $deadlines);
        return back()->with('status', 'Deadline deleted.');
    }

    /**
     * Create or update a legal library chapter / circular (admin-library).
     */
    public function saveLibrary(Request $request): RedirectResponse
    {
        $library = $this->cms->get('library', []);
        $id = (int) $request->input('id', 0);

        $item = [
            'id' => $id > 0 ? $id : (collect($library)->max('id') + 1),
            'code' => strtoupper((string) $request->input('code', 'CH 0' . (count($library) + 1))),
            'type' => (string) $request->input('type', 'Act Chapter'),
            'title' => (string) $request->input('title', 'Legislative Provision'),
            'subtitle' => (string) $request->input('subtitle', 'Legislation · Reader'),
            'book' => strtoupper((string) $request->input('book', 'BOOK 01 / INCOME TAX ACT 2023')),
            'lead' => (string) $request->input('lead', ''),
            'overview' => (string) $request->input('overview', ''),
            'quote' => (string) $request->input('quote', ''),
            'navigation_note' => (string) $request->input('navigation_note', 'Cross-reference with official NBR notifications.'),
            'status' => (string) $request->input('status', 'Published'),
        ];

        $updated = false;
        foreach ($library as $idx => $existing) {
            if ((int) ($existing['id'] ?? 0) === $id && $id > 0) {
                $library[$idx] = $item;
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            $library[] = $item;
        }

        $this->cms->put('library', array_values($library));
        return back()->with('status', 'Legal library item saved and connected to Law Reader & AI Search!');
    }

    public function deleteLibrary(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $library = array_values(array_filter(
            $this->cms->get('library', []),
            fn ($l) => (int) ($l['id'] ?? 0) !== $id
        ));
        $this->cms->put('library', $library);
        return back()->with('status', 'Legal library item removed.');
    }

    /**
     * Update tax, VAT, TDS, and VDS rates (admin-tax-rates).
     */
    public function saveTaxRates(Request $request): RedirectResponse
    {
        $rates = $this->cms->get('tax_rates', []);
        foreach ($rates as $key => $val) {
            if ($request->has($key)) {
                $rates[$key] = (float) $request->input($key);
            }
        }
        $this->cms->put('tax_rates', $rates);
        return back()->with('status', 'Tax, VAT, TDS & VDS statutory rates updated across all calculators!');
    }

    /**
     * Create or update a team member (admin-portfolio).
     */
    public function saveTeam(Request $request): RedirectResponse
    {
        $team = $this->cms->get('team', []);
        $id = (int) $request->input('id', 0);
        $tagsRaw = (string) $request->input('tags', 'Tax Advisory, IFRS, Compliance');
        $tags = array_values(array_filter(array_map('trim', explode(',', $tagsRaw))));

        $item = [
            'id' => $id > 0 ? $id : (collect($team)->max('id') + 1),
            'code' => str_pad((string) ($id > 0 ? $id : (count($team) + 1)), 2, '0', STR_PAD_LEFT),
            'kicker' => strtoupper((string) $request->input('kicker', 'ADVISORY')),
            'name' => (string) $request->input('name', 'Advisory Specialist'),
            'summary' => (string) $request->input('summary', ''),
            'bio' => (string) $request->input('bio', ''),
            'tags' => $tags,
        ];

        $updated = false;
        foreach ($team as $idx => $existing) {
            if ((int) ($existing['id'] ?? 0) === $id && $id > 0) {
                $team[$idx] = $item;
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            $team[] = $item;
        }

        $this->cms->put('team', array_values($team));
        return back()->with('status', 'Team profile saved and updated on the Team page!');
    }

    public function deleteTeam(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $team = array_values(array_filter(
            $this->cms->get('team', []),
            fn ($t) => (int) ($t['id'] ?? 0) !== $id
        ));
        $this->cms->put('team', $team);
        return back()->with('status', 'Team member removed.');
    }

    /**
     * Update enquiry status or delete message (admin-messages).
     */
    public function updateMessageStatus(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $status = (string) $request->input('status', 'Read');
        $enquiries = $this->cms->get('enquiries', []);
        foreach ($enquiries as &$enq) {
            if ((int) ($enq['id'] ?? 0) === $id) {
                $enq['status'] = $status;
                break;
            }
        }
        $this->cms->put('enquiries', $enquiries);
        return back()->with('status', "Message #{$id} marked as {$status}.");
    }

    public function deleteMessage(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $enquiries = array_values(array_filter(
            $this->cms->get('enquiries', []),
            fn ($e) => (int) ($e['id'] ?? 0) !== $id
        ));
        $this->cms->put('enquiries', $enquiries);
        return back()->with('status', 'Message deleted.');
    }

    /**
     * Update Admin / Client credentials (admin-auth & account-settings).
     */
    public function saveAuth(Request $request): RedirectResponse
    {
        $creds = $this->cms->get('admin_credentials', []);
        foreach (['email', 'password', 'name', 'client_name', 'client_email', 'client_org'] as $field) {
            if ($request->filled($field)) {
                $creds[$field] = (string) $request->input($field);
            }
        }
        $this->cms->put('admin_credentials', $creds);
        return back()->with('status', 'Account & security settings saved successfully.');
    }

    /**
     * Update library workflow status (admin-library-workflow).
     */
    public function updateWorkflow(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $status = (string) $request->input('status', 'Published');
        $library = $this->cms->get('library', []);
        foreach ($library as &$item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                $item['status'] = $status;
                break;
            }
        }
        $this->cms->put('library', $library);
        return back()->with('status', "Library item #{$id} workflow status updated to {$status}.");
    }

    /**
     * Client Workspace: Upload/add document to Document Vault (documents).
     */
    public function addDocument(Request $request): RedirectResponse
    {
        $docs = $this->cms->get('documents', []);
        $title = (string) $request->input('title', 'Financial Record.pdf');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $title = $file->getClientOriginalName();
            $size = round($file->getSize() / 1024, 1) . ' KB';
        } else {
            $size = (string) $request->input('size', '420 KB');
        }

        $newDoc = [
            'id' => collect($docs)->max('id') + 1,
            'title' => $title,
            'category' => (string) $request->input('category', 'Tax & Corporate'),
            'size' => $size,
            'uploaded_at' => date('Y-m-d'),
            'status' => 'Verified',
        ];
        array_unshift($docs, $newDoc);
        $this->cms->put('documents', $docs);

        return back()->with('status', "Document '{$title}' added to your Document Vault.");
    }

    public function deleteDocument(Request $request): RedirectResponse
    {
        $id = (int) $request->input('id', 0);
        $docs = array_values(array_filter(
            $this->cms->get('documents', []),
            fn ($d) => (int) ($d['id'] ?? 0) !== $id
        ));
        $this->cms->put('documents', $docs);
        return back()->with('status', 'Document removed from vault.');
    }

    /**
     * Client Workspace: Add tax submission (submissions).
     */
    public function addSubmission(Request $request): RedirectResponse
    {
        $subs = $this->cms->get('submissions', []);
        $nextId = collect($subs)->max('id') + 1;
        $newSub = [
            'id' => $nextId,
            'reference' => 'SUB-' . date('Y') . '-' . (100 + $nextId),
            'title' => (string) $request->input('title', 'Tax Return Submission'),
            'type' => (string) $request->input('type', 'Income Tax'),
            'period' => (string) $request->input('period', '2026–2027'),
            'amount' => '৳ ' . number_format((float) $request->input('amount', 0)),
            'status' => (string) $request->input('status', 'Submitted'),
            'updated_at' => date('Y-m-d'),
        ];
        array_unshift($subs, $newSub);
        $this->cms->put('submissions', $subs);

        return back()->with('status', 'Submission recorded in Client Workspace.');
    }

    /**
     * Auth actions (login / register / logout).
     */
    public function login(Request $request): RedirectResponse
    {
        $email = (string) $request->input('email', '');
        $request->session()->put('df_user_email', $email);
        if (str_contains(strtolower($email), 'admin')) {
            $request->session()->put('df_role', 'admin');
            return redirect()->route('pages.show', 'admin-dashboard')->with('status', 'Welcome back to DhakaFin Admin Studio.');
        }
        $request->session()->put('df_role', 'client');
        return redirect()->route('pages.show', 'client-dashboard')->with('status', 'Signed in to DhakaFin Client Workspace.');
    }

    public function register(Request $request): RedirectResponse
    {
        $name = (string) $request->input('name', 'DhakaFin Client');
        $email = (string) $request->input('email', 'client@dhakafin.com');
        $creds = $this->cms->get('admin_credentials', []);
        $creds['client_name'] = $name;
        $creds['client_email'] = $email;
        if ($request->filled('organisation')) {
            $creds['client_org'] = (string) $request->input('organisation');
        }
        $this->cms->put('admin_credentials', $creds);
        $request->session()->put('df_user_email', $email);
        $request->session()->put('df_role', 'client');

        return redirect()->route('pages.show', 'client-dashboard')->with('status', 'Account created! Welcome to DhakaFin Client Workspace.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['df_user_email', 'df_role']);
        return redirect()->route('home')->with('status', 'You have been signed out.');
    }

    /**
     * Reset CMS store back to factory DhakaFin defaults if needed.
     */
    public function resetDefaults(): RedirectResponse
    {
        $this->cms->save($this->cms->defaults());
        return back()->with('status', 'All website content and settings restored to default DhakaFin state.');
    }
}
