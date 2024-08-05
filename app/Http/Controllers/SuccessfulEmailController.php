<?php

namespace App\Http\Controllers;

use Soundasleep\Html2Text;
use Illuminate\Http\Request;
use App\Models\SuccessfulEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SuccessfulEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emails = SuccessfulEmail::whereNull('deleted_at')->get();
        return response()->json($emails);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Define validation rules
            $validatedData = $request->validate([
                'affiliate_id' => 'required|integer',
                'envelope' => 'required|string',
                'from' => 'required|string|email',
                'subject' => 'required|string',
                'dkim' => 'nullable|string',
                'SPF' => 'nullable|string',
                'spam_score' => 'nullable|numeric',
                'email' => 'required|string',
                'sender_ip' => 'nullable|ip',
                'to' => 'required|string|email',
                'timestamp' => 'required|integer',
            ]);

            // Create a new SuccessfulEmail record
            $email = SuccessfulEmail::create($validatedData);

            // Parse the raw email content to get the plain text
            $rawText = Html2Text::convert($email->email);
            $email->raw_text = $rawText;
            $email->save();

            return response()->json(
                [
                    'message' => "Email data successfully saved!",
                    "data" => $email
                ],
                201
            );
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Attempt to find the record, including only non-deleted records
            $email = SuccessfulEmail::where('id', $id)->whereNull('deleted_at')->firstOrFail();

            return response()->json($email);
        } catch (ModelNotFoundException $e) {
            // If the record is not found, return a 404 response
            return response()->json(['message' => 'Record not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Define validation rules
            $rules = [
                'affiliate_id' => 'sometimes|required|integer',
                'envelope' => 'sometimes|required|string',
                'from' => 'sometimes|required|string|email',
                'subject' => 'sometimes|required|string',
                'dkim' => 'sometimes|nullable|string',
                'SPF' => 'sometimes|nullable|string',
                'spam_score' => 'sometimes|nullable|numeric',
                'email' => 'sometimes|required|string',
                'sender_ip' => 'sometimes|nullable|ip',
                'to' => 'sometimes|required|string|email',
                'timestamp' => 'sometimes|required|integer',
            ];

            // Validate the request with custom messages
            $validatedData = $request->validate($rules);

            // Find the existing email record
            $email = SuccessfulEmail::findOrFail($id);

            // Update only provided fields
            $email->update($validatedData);

            return response()->json($email);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $email = SuccessfulEmail::findOrFail($id);
        $email->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
