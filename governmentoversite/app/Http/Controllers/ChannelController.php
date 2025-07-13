<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use App\Models\Channels;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public $entriesPerPage = 10;
    
    public function index()
    {
        $items = Channels::paginate($this->entriesPerPage);

        //dd($items->first()->VideoCategory);

        return view( "channels.index" )->with('items', $items);
    }

    public function create()
    {
        return view('channels.create');
    }
    
    public function store(Request $request)
    {
        $newChannel = new Channels;
        $newChannel->name = $request->name;
        $newChannel->slug = $request->slug;
        $newChannel->channel_id = $request->channel_id;
        $newChannel->description = $request->description;

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('images/' . $imageName);
    
            // Store the image file
            $image = Image::make($image)->resize(600, 400); // Resize the image if needed
            $image->save($imagePath);
            $newChannel->thumbnail = 'images/' . $imageName;
        }

        $newChannel->save();

        return redirect()->route('channels.index')->with('success', 'Channel created successfully!');
    }
    
    public function edit(int $id)
    {
        $currentChannel = Channels::find($id);
        return view('channels.edit')->with('currentChannel', $currentChannel);
    }
    
    public function update(Request $request, int $id)
    {
        $channel = Channels::findOrFail($id);
        $channel->name = $request->name;
        $channel->slug = $request->slug;
        $channel->channel_id = $request->channel_id;
        $channel->description = $request->description;
        $channel->is_enabled = $request->is_enabled ? '1' : '0';

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('images/' . $imageName);
    
            // Store the image file
            $image = Image::make($image)->resize(600, 400); // Resize the image if needed
            $image->save($imagePath);
            $channel->thumbnail = 'images/' . $imageName;
        }

        $channel->save();

        return redirect()->route('channels.index')->with('success', 'Channel updated successfully!');
    }
    
    public function delete(int $id)
    {
        $channel = Channels::find($id);
        $channel->delete();

        return redirect()->route('channels.index')->with('success', 'Channel deleted successfully!');
    }

    public function live_stream_index()
    {
        $channels = Channels::where('is_enabled', true)->get();

        return view('livestream.index')->with('channels', $channels);
    }

    public function live_stream_channel(string $slug)
    {
        //$channel = Channels::find($slug);
        $channel = Channels::where('slug', $slug)->first();
        $channels = Channels::get();

        $data = [
            'channel' => $channel,
            'channels' => $channels
        ];

        return view('livestream.view')->with($data);
    }
    
}
