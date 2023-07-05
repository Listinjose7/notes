<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Note;


class NotesController extends Controller
{
    public function getNote(Request $request, $noteId)
    {
        
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

       
        $response = Http::get("http://server3/notes/{$noteId}");

      
        $responseData = $response->json();

        
        return response()->json($responseData);
    }
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'note_text' => 'required',
            'file' => 'nullable|file',
        ]);

     
        $note = new Note();
        $note->user_id = $request->user()->id;
        $note->user_profile_id = $request->user()->profile->id;
        $note->note_text = $validatedData['note_text'];

        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $note->file = $fileName;
        }

        $note->save();

      
        return response()->json($note, 201);
    }

    public function update(Request $request, $noteId)
    {
        $validatedData = $request->validate([
            'note_text' => 'required',
            'file' => 'nullable|file',
        ]);

      
        $note = Note::where('id', $noteId)
                    ->where('user_id', $request->user()->id)
                    ->first();

       
        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

       
        $note->note_text = $validatedData['note_text'];

      
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $note->file = $fileName;
        }

        $note->save();

      
        return response()->json($note, 200);
    }

    public function delete(Request $request, $noteId)
    {
        
        $note = Note::where('id', $noteId)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (!$note) {
            return response()->json(['error' => 'Note not found'], 404);
        }

       
        $note->delete();

        
        return response()->json(['message' => 'Note deleted successfully'], 200);
    }


    public function getNote($noteId)
    {
        
        $note = Note::findOrFail($noteId);

        return response()->json($note, 200);
    }
    
}
