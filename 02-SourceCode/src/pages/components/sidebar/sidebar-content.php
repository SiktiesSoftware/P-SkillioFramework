<h3>SIDEBAR CONTENT</h3>

<ul>
<?php $objects =
    [
        ['This is a sidebar item.', "test"],
        'Sidebar Item Title',
        '/sidebar-item-link'
    ];

foreach ($objects as $index => $object): ?>
    <h4><?= $index; ?></h4>
    @child.name="sidebar-item"
    @endchild
<?php endforeach; ?>
</ul>