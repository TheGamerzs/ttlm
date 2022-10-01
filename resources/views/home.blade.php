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
        <a href="https://discord.gg/eYtWhzHz8k">Discord <i class="bi bi-discord text-info"></i></a>
    </p>
    <p>
        Common crafting tree starts:<br>
        <a href="{{ route('craftingPage', ['name' => 'house']) }}">House</a><br>
        <a href="{{ route('craftingPage', ['name' => 'crafted_concrete']) }}">Concrete</a><br>
        <a href="{{ route('craftingPage', ['name' => 'military_explosives']) }}">Explosives</a><br>
        Vehicles Shipments:<br>
        <a href="{{ route('craftingPage', ['name' => 'vehicle_shipment|zr350|Annis ZR-350|car']) }}">Annis ZR-350</a><br>
        <a href="{{ route('craftingPage', ['name' => 'vehicle_shipment|voltic2|Coil Rocket Voltic|car']) }}">Coil Rocket Voltic</a><br>
        <a href="{{ route('craftingPage', ['name' => 'vehicle_shipment|savanna|Coil Savanna|car']) }}">Coil Savanna</a><br>
        <a href="{{ route('craftingPage', ['name' => 'vehicle_shipment|vertice|Hijak Vertice|car']) }}">Hijak Vertice</a><br>
        <a href="{{ route('craftingPage', ['name' => 'vehicle_shipment|futo|Karin Futo|car']) }}">Karin Futo</a><br>
        <a href="{{ route('craftingPage', ['name' => 'vehicle_shipment|landstalker2|Landstalker XL|car']) }}">Landstalker XL</a><br>
        <a href="{{ route('craftingPage', ['name' => 'repair_shop']) }}">Repair Shop</a><br>
    </p>
    <p>
        Less than intuitive item names:<br>
        liquid_water => Treated Water<br>
        liquid_water_raw => Unfiltered Water<br>
        petrochem_waste => Waste Water<br>
        tcargodust => Sawdust<br>
        tcargologs => Logs<br>
        pucargosmall => Tools
    </p>

    <div class="row">
        <div class="col">
            <h3 class="mt-5">Currently:</h3>
            <p>
                Full support for all items in the House and Explosives crafting trees.<br>
                All Vehicle shipments except HVY Nightshark.<br>
                - Rubber is not factored into the needed pickup runs section of quick calculations.
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
                    "Pretty Names" for items, recipes, storages.<br>
                    Customizable Defaults<br>
                    Quick links to TT wiki pages.<br>
                    Customizable full trailer alerts and sellables.<br>
                    Garbage alerts (Logs, Quarry Rubble, etc.)<br>
                    Mobile friendly.<br>
                    Support for SD Card recipes.<br>
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
