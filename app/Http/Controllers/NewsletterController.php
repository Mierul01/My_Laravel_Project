<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\NewsletterUpdated;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::withTrashed()->get();
        return view('newsletters.index', compact('newsletters'));
    }

    public function create()
    {
        return view('newsletters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        $newsletter = Newsletter::create($data);

        // Broadcast the event
        event(new NewsletterUpdated($newsletter));

        return redirect()->route('newsletters.index');
    }

    public function show(Newsletter $newsletter)
    {
        return view('newsletters.show', compact('newsletter'));
    }

    public function edit(Newsletter $newsletter)
    {
        return view('newsletters.edit', compact('newsletter'));
    }

    public function update(Request $request, Newsletter $newsletter)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;

            if ($newsletter->image) {
                unlink(public_path('images').'/'.$newsletter->image);
            }
        }

        $newsletter->update($data);

        // Broadcast the event
        event(new NewsletterUpdated($newsletter));

        return redirect()->route('newsletters.index');
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        // Broadcast the event
        event(new NewsletterUpdated($newsletter));

        return redirect()->route('newsletters.index');
    }

    public function restore($id)
    {
        $newsletter = Newsletter::withTrashed()->find($id);
        if ($newsletter && $newsletter->trashed()) {
            $newsletter->restore();
            $newsletter->update(['restored_at' => Carbon::now()]);

            // Broadcast the event
            event(new NewsletterUpdated($newsletter));
        }

        return redirect()->route('newsletters.index');
    }

    public function forceDelete($id)
    {
        $newsletter = Newsletter::withTrashed()->find($id);
        if ($newsletter) {
            $newsletter->forceDelete();
        }

        return redirect()->route('newsletters.index');
    }

    public function userIndex()
    {
        $newsletters = Newsletter::all();
        return view('newsletters.user_index', compact('newsletters'));
    }

    public function userShow(Newsletter $newsletter)
    {
        return view('newsletters.user_show', compact('newsletter'));
    }

    public function apiIndex()
    {
        $newsletters = Newsletter::all();
        return response()->json($newsletters);
    }

    public function hide(Request $request, $id)
    {
        $newsletter = Newsletter::find($id);
        if ($newsletter) {
            $newsletter->delete(); // Soft delete the newsletter
        }

        return response()->json(['success' => true]);
    }
}


