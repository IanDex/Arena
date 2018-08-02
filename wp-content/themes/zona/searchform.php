<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' )); ?>">
	<fieldset>
		<span class="search--input-wrap">
			<input autocomplete="off" type="text" placeholder="<?php esc_attr_e( 'Buscar', 'zona' ) ?>" value="<?php echo get_search_query(); ?>" name="s" id="s" />
		</span>
		<button type="submit" id="searchsubmit"><i class="icon icon-search"></i></button>
		<button type="submit" id="">Submit</button>
	</fieldset>
</form>