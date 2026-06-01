@extends('Players.Layouts.dashboard')

@section('title', 'Kích hoạt sản phẩm')

@section('dashboard_content')
<div class="max-w-md mx-auto bg-[#171a21] p-8 border border-gray-800 rounded-sm">
    <h2 class="text-white font-bold text-lg mb-4">Kích hoạt sản phẩm trên hệ thống</h2>
    <p class="text-gray-400 text-xs mb-6">Vui lòng nhập mã kích hoạt (CD Key) mà bạn nhận được sau khi mua hàng.</p>

    <form action="{{ route('library.redeem.post') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="key_code" placeholder="Nhập mã (VD: KEY-XXXX-XXXX)" 
               class="w-full bg-[#101822] border border-gray-700 text-white px-4 py-3 rounded-sm outline-none focus:border-sky-500">
        
        <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-bold py-3 rounded-sm transition">
            KÍCH HOẠT NGAY
        </button>
    </form>
    
    @if(session('error'))
        <p class="text-red-500 text-xs mt-4">{{ session('error') }}</p>
    @endif
</div>
@endsection