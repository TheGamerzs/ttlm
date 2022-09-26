<x-layout title-addon="Settings">
    <livewire:user-settings :user="$user"/>
    <div class="row">
        <div class="col-6 offset-3">
            <hr>
            <div class="mt-5">
                <h2 class="text-center">What is the Private API Key?</h2>
                <p>
                    Transport Tycoon's Public API is a service they offer to be able to pull data from the game itself.
                    This tool makes use of that to get information about what you have across your storages in game.
                    Full details can be found on their wiki <a href="https://dash.tycoon.community/wiki/index.php/API#API_Keys">here</a>.
                </p>
                <h3 class="text-center">TL;DR</h3>
                <p>
                    To get your key, you need to generate it in game by entering into the chat box:<br>
                </p>
                <pre>/api private new</pre>
                <p>
                    If you already have one, or need to access it again after already having one:<br>
                </p>
                <pre>/api private copy</pre>
                <h4>Charges</h4>
                <p>
                    TT uses a concept of 'charges' for accessing data. Each API call (each time you ask the server for information)
                    costs one charge. When you first generate a new one, you will be given 1,000 for free to begin with.
                    When you need more, they can be purchased in game with <span class="fw-bold">in game currency</span> for $1000 each.
                    To do so, again using the in game chat box, you can purchase them at 1,000 for $1M:
                </p>
                <pre>/api private refill</pre>
                <p>Or you can specify how many you want, <span class="fw-bold">x</span> at $1000 each:</p>
                <pre>/api private refill x</pre>
                <p>
                    Within this tool, actions that make an API call and use a charge are denoted with buttons that look
                    like the following, with how many charges you currently have in parentheses.
                </p>
                <button class="btn btn-warning">This Button Doesn't Actually Do Anything (999)</button>
                <p class="mb-5 mt-2">
                    For any further questions, feel free to reach out to me on discord xxdalexx#9783
                </p>
            </div>
        </div>
    </div>
</x-layout>
