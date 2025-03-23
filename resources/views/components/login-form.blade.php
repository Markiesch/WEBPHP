<form class="flex flex-col gap-6">
    <div class="flex flex-col items-center gap-2 text-center">
        <h1 class="text-2xl font-bold">Log in op uw account</h1>
        <p class="text-balance text-sm text-muted-foreground">
            Voer uw gegevens in om in te loggen
        </p>
    </div>
    <div class="grid gap-6">
        <div class="grid gap-2">
            <label class="font-medium" for="email">E-mail</label>
            <input class="uk-input" id="email" type="email" required />
        </div>
        <div class="grid gap-2">
            <div class="flex items-center">
                <label class="font-medium" for="password">Wachtwoord</label>
                <a href="#" class="ml-auto text-sm underline-offset-4 hover:underline">
                    Wachtwoord vergeten?
                </a>
            </div>
            <input class="uk-input" id="password" type="password" required />
        </div>
        <button type="submit" class="uk-btn uk-btn-primary w-full">
            Inloggen
        </button>
    </div>
    <div class="text-center text-sm">
        Heeft u nog geen account?
        <a href="#" class="underline underline-offset-4">Registreren</a>
    </div>
</form>
