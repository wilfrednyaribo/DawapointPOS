<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Added for logging errors

class ContactController extends Controller
{
    /**
     * Handle Form Submission from Frontend
     */
    public function store(Request $request)
    {
        try {
            // 1. Validate the incoming request data
            // This ensures 'full_name', 'email', etc. are present and correct
            $validated = $request->validate([
                'full_name'      => 'required|string|max:255',
                'phone_number'   => 'required|string|max:20',
                'pharmacy_name'  => 'nullable|string|max:255',
                'email'          => 'required|email|max:255',
                'message'        => 'required|string',
            ]);

            // 2. Attempt to save to Database
            $contactMessage = ContactMessage::create($validated);

            // Check if create was successful
            if ($contactMessage) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Message sent successfully!'
                ], 200);
            } else {
                // This rarely happens, but good to handle
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to save message to database.'
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 3. Handle Validation Errors (e.g., missing email)
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $e->errors() // Returns specific field errors
            ], 422);

        } catch (\Exception $e) {
            // 4. Handle General Errors (Database connection, etc.)
            // Log the error to storage/logs/laravel.log for debugging
            Log::error('Contact Form Submission Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while sending your message. Please try again later.'
                // Note: In production, don't send $e->getMessage() to the user for security
            ], 500);
        }
    }

    /**
     * Show Messages in Admin Dashboard
     */
    public function index()
    {
        // Get all messages, ordered by newest first
        // using latest() is cleaner than orderBy('created_at', 'desc')
        $messages = ContactMessage::latest()->get();
        
        return view('admin.contacts.index', compact('messages'));
    }
}