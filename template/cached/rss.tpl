		<title>{PAGE_TITLE}</title>
		<description>{PAGE_DESCRIPTION}</description>
		<lastBuildDate>{lastBuildDate}</lastBuildDate>
		<link>{ROOT_PATH}</link>
		<!-- LOOP map -->
		<item>
			<title>{map.title}</title>
			<description>{map.description}</description>
			<pubDate>{map.date}</pubDate>
			<link>{ROOT_PATH}{map.game_guid}/{map.gametype_guid}/{map.map_guid}-{map.id}</link>
		</item>
		<!-- END map -->
