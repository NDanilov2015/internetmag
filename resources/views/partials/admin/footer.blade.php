<footer class="main-footer">
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2018 &copy; InternetMag by NDanilov. <a href="http://yandex.ru" title="Made in Russia" target="_blank">Go to my portfolio</a>
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->

</footer>

<!-- LEFT MENU POINTER MOVE SCRIPT -->
<script type="text/javascript">
	jQuery(document).ready(function() {			
		let realPath = window.location.pathname;
		$(".page-sidebar-menu-light li").removeClass("active open");
		let target_li = $("a[href*='"+realPath+"']").parent(); //GTD: need check if href is contained in realPath
		target_li.addClass("active open");
	});
</script>
<!-- END MENU POINTER MOVE SCRIPT -->