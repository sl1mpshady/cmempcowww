<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="media/js/jquery.min.js"></script>
<script src="media/js/bootstrap.min.js"></script>
<link href="media/css/bootstrap.min.css" rel="stylesheet" />
<script src="media/js/bootbox.min.js"></script>

</head>

<body>
<script>
bootbox.dialog({
  message: "Are you sure you want to delete me?",
  title: "Delete",
  buttons: {
    success: {
      label: "Success!",
      className: "btn-success",
      callback: function() {
        Example.show("great success");
      }
    },
    danger: {
      label: "Danger!",
      className: "btn-danger",
      callback: function() {
        Example.show("uh oh, look out!");
      }
    },
    main: {
      label: "Click ME!",
      className: "btn-primary",
      callback: function() {
        Example.show("Primary button");
      }
    }
  }
});
</script>
</body>
</html>