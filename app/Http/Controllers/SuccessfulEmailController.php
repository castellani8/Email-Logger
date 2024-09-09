<?php

namespace App\Http\Controllers;

use App\Helpers\EmailFormatterHelper;
use App\Models\SuccessfulEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuccessfulEmailController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'affiliate_id' => ['required', 'integer'],
            'envelope'     => ['required', 'string'],
            'from'         => ['required', 'string', 'max:255'],
            'subject'      => ['required', 'string'],
            'dkim'         => ['nullable', 'string', 'max:255'],
            'SPF'          => ['nullable', 'string', 'max:255'],
            'spam_score'   => ['nullable', 'numeric'],
            'email'        => ['required', 'string'],
            'sender_ip'    => ['nullable', 'string', 'max:50'],
            'to'           => ['required', 'string'],
            'timestamp'    => ['required', 'integer'],
        ]);

        try {
            $successfulEmail = SuccessfulEmail::query()
                ->create($validated);
        } catch (\Exception $e) {
            error_log($e);
            return response()->json('Error saving the email, please contact us.', 500);
        }

        return response()->json($successfulEmail, 201);
    }

    public function getById(string $id): JsonResponse
    {
        try{
            $successfulEmail = SuccessfulEmail::query()
                ->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json('Record not found', 404);
        }

        return response()->json($successfulEmail, 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try{
            $successfulEmail = SuccessfulEmail::query()
                ->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json('Record not found', 404);
        }

        $validated = $request->validate([
            'affiliate_id' => ['sometimes', 'integer'],
            'envelope'     => ['sometimes', 'string'],
            'from'         => ['sometimes', 'string', 'max:255'],
            'subject'      => ['sometimes', 'string'],
            'dkim'         => ['nullable', 'string', 'max:255'],
            'SPF'          => ['nullable', 'string', 'max:255'],
            'spam_score'   => ['nullable', 'numeric'],
            'email'        => ['sometimes', 'string'],
            'sender_ip'    => ['nullable', 'string', 'max:50'],
            'to'           => ['sometimes', 'string'],
            'timestamp'    => ['sometimes', 'integer'],
        ]);

        if (!empty($validated['email'])) {
            $validated['raw_text'] = EmailFormatterHelper::extractPlainTextFromHtml($validated['email']);
        }

        try {
            $successfulEmail->update($validated);
        } catch (\Exception $e) {
            error_log($e);
            return response()->json('Error updating the email, please contact us.', 500);
        }

        return response()->json($successfulEmail, 200);
    }

    public function deleteById(string $id): JsonResponse
    {
        try{
            $successfulEmail = SuccessfulEmail::query()
                ->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json('Record not found', 404);
        }

        $successfulEmail->delete();

        return response()->json([
            'message' => 'Record deleted successfully',
            'status' => 'success'
        ], 200);
    }

    public function getAll(): JsonResponse
    {
        $successfulEmails = SuccessfulEmail::all();
        return response()->json($successfulEmails, 200);
    }
}
