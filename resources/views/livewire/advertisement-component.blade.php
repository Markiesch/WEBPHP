<div>
    <h1>{{ $advertisement->title }}</h1>
    <p>{{ $advertisement->description }}</p>
    <p>Price: {{ $advertisement->price }}</p>
    <div>
        <img src="{{ \Storage::url('public/qrcodes/' . $advertisement->id . '.png') }}" alt="QR Code">
    </div>
</div>
