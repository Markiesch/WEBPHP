<!DOCTYPE html>
<html>
<head>
    <title>Business Registration Contract</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold text-center mb-6">Business Registration Contract</h1>
    <div class="contract-details">
        <p class="mb-4"><strong>Name:</strong> {{ $name }}</p>
        <p class="mb-4"><strong>Email:</strong> {{ $email }}</p>
        <p class="mb-4"><strong>Description:</strong> {{ $description }}</p>
    </div>
</div>
</body>
</html>
