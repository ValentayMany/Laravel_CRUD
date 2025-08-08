{{-- resources/views/posts/index.blade.php

  ไฟล์ Blade นี้แสดงรายการโพสต์ทั้งหมด (All Post)
  คอมเมนต์ถูกเขียนเป็นภาษาไทยอธิบายการทำงานทีละบรรทัด/บล็อก
--}}

{{-- ขยาย (extend) เทมเพลตหลัก ชื่อ view: "layout" (ไฟล์ที่อยู่ที่ resources/views/layout.blade.php)
   ซึ่งโดยปกติจะมี <head>, header, footer และ @yield('content') ไว้ให้เราแทนที่
--}}
@extends('layout')
@section('title','All Post')
{{-- กำหนดส่วนเนื้อหา (section) ให้กับ layout ที่มี @yield('content') --}}
@section('content')

    {{-- หัวเรื่องของหน้าจอ --}}
    <h1>All Post</h1>

    {{-- ปุ่มลิงก์ไปยังหน้า create
         - route('create') ต้องมีการกำหนดชื่อ route นี้ใน web.php หรือ controller
         - แนะนำตั้งชื่อ route แบบมี namespace เช่น posts.create เพื่อความชัดเจน
    --}}
    <a href="{{ route('create') }}" class="btn btn-primary mb-3">Creact New Post</a>

    {{-- แสดงข้อความแจ้งเตือนถ้ามี session key ชื่อ 'success'
         - session('success') มักถูกตั้งผ่านการ redirect()->with('success', 'ข้อความ') ใน Controller
    --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ตรวจสอบว่ามีโพสต์หรือไม่
         - $posts ควรจะถูกส่งมาจาก Controller (เช่น: return view('posts.index', compact('posts')))
         - การใช้ $posts->count() จะนับจำนวนแถว; อีกทางเลือกคือ $posts->isNotEmpty() หรือ $posts->isEmpty()
    --}}
    @if ($posts->count())

        {{-- วนลูปแสดงแต่ละโพสต์ --}}
        @foreach ($posts as $post)
            <div class="card mb-3">
                <div class="card-body">

                    {{-- แสดงชื่อเรื่องโพสต์
                         - {{ $post->title }} คือการ echo แบบ Blade ซึ่งจะ escape HTML อัตโนมัติ
                    --}}
                    <h3>{{ $post->title }}</h3>

                    {{-- แสดงเนื้อหาแบบตัดทอน (limit) เท่ากับ 100 ตัวอักษร
                         - ใช้ Illuminate\Support\Str::limit เพื่อให้ข้อความไม่ยาวเกินไป
                         - หากต้องการให้ HTML ภายใน content แสดงผลจริง (ไม่ escape) ให้ใช้ {!! $post->content !!}
                         - แต่โปรดระวัง XSS ถ้าแหล่งข้อมูลมาจากผู้ใช้
                    --}}
                    <p>{{ Illuminate\Support\Str::limit($post->content, 100) }}</p>

                    {{-- ปุ่มดูรายละเอียดและแก้ไข
                         - route('show', $post) จะใช้ implicit route-model binding ถ้ารูปแบบ route รับพารามิเตอร์ {post}
                         - ถ้า route ถูกตั้งชื่อต่างออกไป ต้องแก้ชื่อ route ให้ตรงกัน
                    --}}
                    <a href="{{ route('show', $post) }}" class="btn btn-secondary">View</a>
                    <a href="{{ route('edit', $post) }}" class="btn btn-warning">Edit</a>

                    {{-- ฟอร์มสำหรับลบโพสต์
                         - method="POST" แต่ใช้ @method('DELETE') เพื่อ spoof เป็น HTTP DELETE
                         - ต้องมี @csrf เสมอเพื่อป้องกัน CSRF
                         - onsubmit พูดคุยกับ JS confirm เพื่อให้ผู้ใช้ยืนยันก่อนลบ
                         - style="display: inline" เพื่อให้ปุ่ม Delete อยู่ในแถวเดียวกับปุ่มอื่น
                    --}}
                    <form action="{{ route('destroy', $post) }}" method="POST" style="display: inline" onsubmit="return confirm('Are you sure to delete?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>

                </div>
            </div>
        @endforeach

    @else

        {{-- กรณีไม่มีโพสต์ จะแสดงข้อความแจ้งเตือน --}}
        <div class="alert alert-info">No Post Found</div>

    @endif

@endsection

{{--
  ข้อเสนอแนะเพิ่มเติม / best practices:
  1. ตรวจสอบว่า Controller ส่งตัวแปร $posts มา (ตัวอย่าง: $posts = Post::latest()->paginate(10);)
  2. แนะนำใช้ pagination เช่น $posts->links() แทนการแสดงทั้งหมดในครั้งเดียว
  3. ใช้ชื่อ route ที่ชัดเจนและตาม convention เช่น routes/web.php -> Route::resource('posts', PostController::class);
     จากนั้นใน view จะเรียก route('posts.create'), route('posts.show', $post) ฯลฯ
  4. ควรตรวจสอบ authorization (เช่น @can('update', $post)) ก่อนแสดงปุ่ม Edit/Delete
  5. ระวังการแสดงผล HTML ดิบจากผู้ใช้ (ถ้าต้องการแสดง HTML ให้ sanitize ก่อน)
  6. ใช้ @empty / @forelse ถ้าต้องการรูปแบบเขียนที่กระชับกว่า (เช่น @forelse ($posts as $post) ... @empty ... @endforelse)
--}}
