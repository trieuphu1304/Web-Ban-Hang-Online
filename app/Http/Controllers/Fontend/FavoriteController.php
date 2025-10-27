<?php

namespace App\Http\Controllers\Fontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login.index');
        }

        $template = 'fontend.favorite.index';
        
        // Lấy danh sách sản phẩm yêu thích của user hiện tại
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product') // eager load product relationship
            ->get();

        return view('fontend.layout', compact('template', 'favorites'));
    }
    
    public function list(Request $request)
    {
        if (Auth::check()) {
            $ids = Favorite::where('user_id', Auth::id())->pluck('product_id')->toArray();
            return response()->json(['success' => true, 'favorites' => $ids]);
        }
        return response()->json(['success' => true, 'favorites' => []]);
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $pid = (int)$request->product_id;
        $uid = Auth::id();
        $fav = Favorite::where('user_id', $uid)->where('product_id', $pid)->first();
        if ($fav) {
            $fav->delete();
            return response()->json(['success' => true, 'action' => 'removed', 'product_id' => $pid]);
        } else {
            Favorite::create(['user_id' => $uid, 'product_id' => $pid]);
            return response()->json(['success' => true, 'action' => 'added', 'product_id' => $pid]);
        }
    }
}