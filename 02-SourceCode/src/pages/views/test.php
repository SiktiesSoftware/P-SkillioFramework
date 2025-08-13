<h1>Users test page</h1> <hr> This page has priority over the user/{id} route

<div>
    <p>Number of infos for john: {{ count($john) }}</p>
    <hr>

    <?php $i = 0; ?>
    @foreach($john as $key => $value)
        <?php $i++; ?>
        <br>
        @child.name="test"
    @endforeach
</div>