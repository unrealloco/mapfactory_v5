
				<!-- LOOP moreMapFrom -->
                <li class="line {moreMapFrom.class}">
					<a href="{ROOT_PATH}{moreMapFrom.game_guid}/{moreMapFrom.gametype_guid}/{moreMapFrom.map_guid}-{moreMapFrom.id}" class="preview">
                        <img src="{ROOT_PATH}screenshot/80x60/{moreMapFrom.map_guid}-{moreMapFrom.image}.jpg" width="80px" height="60px" alt="{moreMapFrom.title}" title="{moreMapFrom.game} {moreMapFrom.title}">
                    </a>

                    <ul>
                        <li>
                            <a href="{ROOT_PATH}{moreMapFrom.game_guid}/{moreMapFrom.gametype_guid}/{moreMapFrom.map_guid}-{moreMapFrom.id}" alt="{moreMapFrom.title}" title="{moreMapFrom.game} {moreMapFrom.title}">
                                <strong>{moreMapFrom.title}</strong>
                            </a>
                        </li>
                        <li>
                            <a href="{ROOT_PATH}{moreMapFrom.game_guid}" alt="{moreMapFrom.game}" title="{moreMapFrom.game} custom maps">{moreMapFrom.game}</a>
                        </li>
                        <li>
                            <a href="{ROOT_PATH}{moreMapFrom.game_guid}/{moreMapFrom.gametype_guid}" alt="{moreMapFrom.gametype}" title="{moreMapFrom.game} {moreMapFrom.gametype}">{moreMapFrom.gametype}</a>
                        </li>
                    </ul>
				</li>
				<!-- END moreMapFrom -->
