@extends('layouts.app')

@section('heading')
    Contracts
@endsection

@section('content')
    <div class="flex justify-end mb-6">
        <a href="{{ route('contracts.showUploadForm') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload Contract</a>
    </div>
    @foreach($contracts as $contract)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="text-xl font-bold">{{ $contract->name }}</h5>
                    <p class="text-gray-700">{{ $contract->email }}</p>
                    <p class="text-gray-700">{{ $contract->description }}</p>
                </div>
                <form action="{{ route('export-pdf', $contract->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Export to PDF
                    </button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
