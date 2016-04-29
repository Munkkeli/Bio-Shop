<?php
  
  include 'php/tables.php';

  // Route a request to the right controller
  function route($type, $action, $data) {
    if (empty($type) || empty($action) || empty($data)) return;

    switch ($action) {
      case 'palvelu':
        Palvelu::route($type, $data);
        break;

      case 'tapahtuma':
        Tapahtuma::route($type, $data);
        break;
      
      case 'hoitaja':
        Hoitaja::route($type, $data);
        break;

      default:
        break;
    }

    unset($_POST);
    header('Location: ' . $GLOBALS['prefix'] . '/admin.php');
    return;
  }

  // Do final request routing
  if (isset($_POST['_action']) && isset($_POST['_type']))
    route($_POST['_action'], $_POST['_type'], $_POST);
  else
    return header('Location: ' . $GLOBALS['prefix']);

?>