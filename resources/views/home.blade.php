<x-layout title-addon="Home">
    <h2 class="mb-5">Still in early development</h2>
    <p>
        This started out as a quick personal project to make my life easier. <br>
        I'm still in the process of taking all the completed features and converting the code over for multiple users to use.<br>
        It's likely for you to come across errors that I haven't accounted for yet, and I'm actively working on solving those issues.
    </p>
    <p>
        My end goal is to make this the ultimate tool possible within the limits of the data available.
    </p>
    <p>
        Common crafting tree starts:<br>
        <a href="{{ route('craftingPage', ['name' => 'house']) }}">House</a><br>
        <a href="{{ route('craftingPage', ['name' => 'crafted_concrete']) }}">Concrete</a><br>
        <a href="{{ route('craftingPage', ['name' => 'military_explosives']) }}">Explosives</a><br>
    </p>
    <div class="row">
        <div class="col">
            <h3 class="mt-5">Currently:</h3>
            <p>
                Full support for all items in the House crafting tree.<br>
                Partial support for the Explosives tree.<br>
                - All recipes are in and you can make full use of the main section of the crafting page.<br>
                - Pickup Run and Train Yard Calculators, and Shopping List support to come.
            </p>
        </div>
        <div class="col d-flex justify-content-end text-end">
            <div>
                <h3 class="mt-5">Roadmap/In Development</h3>
                <p>
                    Bug Fixes will always take priority.<br>
                </p>
                <p>
                    Support for the rest of the crafting trees.<br>
                    Support for SD Card recipes.<br>
                    "Pretty Names" for items, recipes, storages.<br>
                    Quick links to TT wiki pages.<br>
                    Customizable full trailer alerts and sellables.<br>
                    Garbage alerts (Logs, Quarry Rubble, etc.)<br>
                    Mobile friendly.<br>
                    Rework of Storage Management.<br>
                    Themes?
                </p>
                <p>
                    And anything else you, or I, come up with that can make life even easier.<br>
                    If you're still busting out a calculator for something, I want to know!
                </p>
            </div>

        </div>
    </div>


</x-layout>
