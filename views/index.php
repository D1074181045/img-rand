<html>
<?php include("layout/head.php"); ?>
<body>
	<div class="center">
		<?php include("layout/instruction.php"); ?>
		<div style="padding: 5px;border: 1px solid #c5c5c5;border-radius: 5px;margin-bottom: 20px;">
			<h3 style="text-align:center">添加網址</h3>
			<div style="overflow-y: scroll;max-height: 215px;" id="url_list">
				<?php 
					if (isset($img_url_items)) {
						for ($i = 0; $i < count($img_url_items); $i++) {
				?>
				<div style="display: flex;margin-bottom: 5px;">
				<input class="form-control" autocomplete="off" name="img_url" type="text" value="<?php echo $img_url_items[$i] ?>"/>
				<button class="btn btn-primary" style="margin-left:5px">-</button>
				</div>
				<?php	
						}
					} 
				?>
			</div>
			<input class="form-control" autocomplete="off" id="io_url" type="text" style="margin-bottom: 5px;border: 1px solid #68c4c2;"/>
			<button class="btn btn-primary btn-block" id="url_add" style="width:100%; margin-bottom:5px">+</button>
		</div>
		<?php include("layout/generate.php"); ?>
	</div>
</body>
<?php include("layout/scripts.php"); ?>
<script>
	function load_btn_event() {
		let child_array = [...document.getElementById("url_list").children];

		child_array.forEach(({children}) => {
			children[1].addEventListener('click', ({target}) => {
				target.parentNode.remove();
			});
		});
	}
	load_btn_event();
</script>
</html>