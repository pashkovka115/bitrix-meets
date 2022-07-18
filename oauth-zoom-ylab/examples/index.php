<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Bitrix\Main\Loader::includeModule('ylab.meetings');

?>
<p>
    <a href="/oauth-zoom-ylab/examples/index.php?list=Y">List</a><br><br>
</p>

<?php
if (isset($_GET['list'])){

    $meeting = new \Ylab\Meetings\Zoom\Meeting();

    $meetings = $meeting->list();
    echo '<pre>';
    print_r($meetings);
    echo '</pre>';

    foreach ($meetings->meetings as $item){
        echo '<pre>===========';

        print_r($meeting->getById($item->id));

        echo '===========</pre>';
    }
}
