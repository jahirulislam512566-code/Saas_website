<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Website;
use App\Models\FormSubmission;
use Illuminate\Support\Str;

class FormService
{
    public function createForm(Website $website, array $data): Form
    {
        $form = Form::create([
            'website_id' => $website->id,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'fields' => $data['fields'] ?? [],
            'settings' => $data['settings'] ?? [],
            'success_message' => $data['success_message'] ?? 'Thank you for your submission!',
            'error_message' => $data['error_message'] ?? 'There was an error submitting the form.',
            'email_notifications' => $data['email_notifications'] ?? [],
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $form;
    }

    public function updateForm(Form $form, array $data): Form
    {
        $form->update($data);
        return $form;
    }

    public function deleteForm(Form $form): bool
    {
        return $form->delete();
    }

    public function getWebsiteForms(Website $website)
    {
        return $website->forms()->where('is_active', true)->get();
    }

    public function getFormBySlug(Website $website, string $slug): ?Form
    {
        return $website->forms()->where('slug', $slug)->first();
    }

    public function submitForm(Form $form, array $data): FormSubmission
    {
        // Validate data against form fields
        $this->validateFormData($form, $data);

        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'submitted_at' => now(),
        ]);

        // Process notifications
        $this->sendFormNotifications($form, $submission);

        // Process webhooks
        $this->processWebhooks($form, $submission);

        return $submission;
    }

    public function getFormSubmissions(Form $form)
    {
        return $form->submissions()->orderBy('submitted_at', 'desc')->get();
    }

    public function getFormStats(Form $form): array
    {
        $submissions = $form->submissions;
        
        return [
            'total_submissions' => $submissions->count(),
            'last_7_days' => $submissions->where('submitted_at', '>=', now()->subDays(7))->count(),
            'last_30_days' => $submissions->where('submitted_at', '>=', now()->subDays(30))->count(),
            'views' => $form->views ?? 0,
            'conversion_rate' => $form->views > 0 
                ? round(($submissions->count() / $form->views) * 100, 2) 
                : 0,
        ];
    }

    public function getSubmissionById(Form $form, int $submissionId): ?FormSubmission
    {
        return $form->submissions()->find($submissionId);
    }

    public function deleteSubmission(FormSubmission $submission): bool
    {
        return $submission->delete();
    }

    public function exportSubmissions(Form $form, string $format = 'csv'): string
    {
        $submissions = $form->submissions;
        
        if ($format === 'csv') {
            return $this->generateCSV($submissions);
        } elseif ($format === 'json') {
            return $this->generateJSON($submissions);
        }
        
        throw new \Exception('Unsupported export format');
    }

    public function toggleForm(Form $form): bool
    {
        $form->is_active = !$form->is_active;
        $form->save();
        return true;
    }

    public function duplicateForm(Form $form): Form
    {
        $newForm = $form->replicate();
        $newForm->name = $form->name . ' (Copy)';
        $newForm->slug = Str::slug($newForm->name);
        $newForm->save();
        return $newForm;
    }

    public function getFormTemplates(): array
    {
        return [
            'contact' => [
                'name' => 'Contact Form',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject', 'required' => true],
                    ['name' => 'message', 'type' => 'textarea', 'label' => 'Message', 'required' => true],
                ],
                'success_message' => 'Thank you for contacting us! We will get back to you soon.',
            ],
            'newsletter' => [
                'name' => 'Newsletter Signup',
                'fields' => [
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'name', 'type' => 'text', 'label' => 'First Name'],
                ],
                'success_message' => 'Thank you for subscribing to our newsletter!',
            ],
            'feedback' => [
                'name' => 'Feedback Form',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Your Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'rating', 'type' => 'select', 'label' => 'Rating', 'required' => true, 'options' => ['1', '2', '3', '4', '5']],
                    ['name' => 'feedback', 'type' => 'textarea', 'label' => 'Feedback', 'required' => true],
                ],
                'success_message' => 'Thank you for your valuable feedback!',
            ],
            'registration' => [
                'name' => 'Event Registration',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => true],
                    ['name' => 'event_date', 'type' => 'date', 'label' => 'Event Date', 'required' => true],
                    ['name' => 'notes', 'type' => 'textarea', 'label' => 'Additional Notes'],
                ],
                'success_message' => 'You have been registered for the event!',
            ],
        ];
    }

    public function getFormFieldTypes(): array
    {
        return [
            'text' => 'Single Line Text',
            'textarea' => 'Multi Line Text',
            'email' => 'Email',
            'tel' => 'Phone Number',
            'number' => 'Number',
            'date' => 'Date',
            'time' => 'Time',
            'datetime' => 'Date & Time',
            'select' => 'Dropdown',
            'checkbox' => 'Checkbox',
            'radio' => 'Radio Group',
            'file' => 'File Upload',
            'hidden' => 'Hidden Field',
            'url' => 'URL',
            'color' => 'Color Picker',
            'range' => 'Range Slider',
        ];
    }

    private function validateFormData(Form $form, array $data): void
    {
        $rules = [];
        foreach ($form->fields as $field) {
            if (isset($field['required']) && $field['required']) {
                $rules[$field['name']] = 'required';
                
                if ($field['type'] === 'email') {
                    $rules[$field['name']] .= '|email';
                } elseif ($field['type'] === 'url') {
                    $rules[$field['name']] .= '|url';
                } elseif ($field['type'] === 'number') {
                    $rules[$field['name']] .= '|numeric';
                }
            }
        }
        
        validator($data, $rules)->validate();
    }

    private function sendFormNotifications(Form $form, FormSubmission $submission): void
    {
        foreach ($form->email_notifications as $notification) {
            $emailService = app(EmailService::class);
            $emailService->sendEmail([
                'to' => $notification['email'],
                'subject' => 'New Form Submission: ' . $form->name,
                'html_body' => view('emails.form-submission', [
                    'form' => $form,
                    'submission' => $submission,
                ])->render(),
            ]);
        }
    }

    private function processWebhooks(Form $form, FormSubmission $submission): void
    {
        foreach ($form->settings['webhooks'] ?? [] as $webhook) {
            Http::post($webhook['url'], [
                'form_id' => $form->id,
                'submission_id' => $submission->id,
                'data' => $submission->data,
                'submitted_at' => $submission->submitted_at,
            ]);
        }
    }

    private function generateCSV($submissions): string
    {
        if ($submissions->isEmpty()) {
            return '';
        }

        $headers = ['ID', 'Submitted At'];
        $firstSubmission = $submissions->first();
        foreach (array_keys($firstSubmission->data) as $key) {
            $headers[] = $key;
        }

        $csv = implode(',', $headers) . "\n";

        foreach ($submissions as $submission) {
            $row = [
                $submission->id,
                $submission->submitted_at,
            ];
            foreach ($submission->data as $value) {
                $row[] = '"' . str_replace('"', '""', $value) . '"';
            }
            $csv .= implode(',', $row) . "\n";
        }

        return $csv;
    }

    private function generateJSON($submissions): string
    {
        return $submissions->toJson();
    }

    public function incrementFormViews(Form $form): void
    {
        $form->increment('views');
    }
}