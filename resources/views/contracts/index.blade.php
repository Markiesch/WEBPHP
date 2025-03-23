@extends('layouts.app')

@section('title', 'Contracts')

@section('content')
    <div class="container mx-auto p-4 min-h-screen flex flex-col items-center">
        <div class="w-full max-w-4xl">
            <h1 class="text-3xl font-bold mb-6 text-center">Contracts</h1>
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
        </div>
    </div>
@stop
