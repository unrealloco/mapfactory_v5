
		<form id="search" action="{ROOT_PATH}" method="post">
			<select id="search_game" name="search_game" onChange="javascript:searchSubmit(false, true, true);">
				<option value="0">All games ...</option>
				<!-- LOOP game -->
				<option value="{game.guid}"{game.selected}>{game.name}</option>
				<!-- END game -->
			</select>

			<!-- SECTION search_gametype -->
			<select id="search_gametype" name="search_gametype" onChange="javascript:searchSubmit(false, false, true);">
				<option value="0">All gametypes ...</option>
				<!-- LOOP gametype -->
				<option value="{gametype.guid}"{gametype.selected}>{gametype.name}</option>
				<!-- END gametype -->
			</select>
			<!-- END search_gametype -->

			<input type="text" value="{search_query}" id="search_query" name="q" class="field">

            <input type="hidden" value="1" name="search">

			<input type="submit" value="Search" name="searchForm" class="submit">
		</form>
