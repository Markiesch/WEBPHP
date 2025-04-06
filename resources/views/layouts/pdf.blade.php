<!DOCTYPE html>
<html>
<head>
    <title>Business Registration Contract</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-10 font-sans">
<div class="text-center pb-6 mb-8 border-b-2 border-gray-800">
    <h1 class="text-3xl font-bold">BUSINESS REGISTRATION CONTRACT</h1>
</div>

<div class="text-right mb-8">
    <p class="text-gray-600">Date: {{ date('d/m/Y') }}</p>
</div>

<div class="mb-8">
    <p class="text-gray-600">Contract Reference: CTR-{{ time() }}</p>
</div>

<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">1. BUSINESS DETAILS</h2>
    <p class="mb-2"><span class="font-semibold">Business Name:</span> {{ $name }}</p>
    <p class="mb-2"><span class="font-semibold">Contact Email:</span> {{ $email }}</p>
</div>

<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">2. CONTRACT DESCRIPTION</h2>
    <p class="text-gray-700">{{ $description }}</p>
</div>

<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">3. ATTACHED DOCUMENTS</h2>
    <p class="mb-2">Original File: {{ basename($contract->file_path) }}</p>
    <p>Upload Date: {{ $contract->created_at->format('d/m/Y H:i') }}</p>
</div>

<div class="mt-24 flex justify-between">
    <div class="text-center">
        <div class="w-48 border-t border-black mx-auto mb-2"></div>
        <p class="font-semibold">Business Representative</p>
        <p>{{ $name }}</p>
    </div>
    <div class="text-center">
        <div class="w-48 border-t border-black mx-auto mb-2"></div>
        <p class="font-semibold">Company Representative</p>
        <p>BAZAAR</p>
    </div>
</div>

<div class="mt-12 pt-4 border-t border-gray-300">
    <p class="text-sm text-gray-600">This document was generated automatically. Please retain a copy for your records.</p>
</div>
</body>
</html>
