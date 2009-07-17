
    <h3>Recent activity</h3>

    <ul class="block activity" id="latestActivity">
        <!-- LOOP activity -->
        <li class="map" name="{activity.image}">
            <a href="{ROOT_PATH}{activity.game_guid}/{activity.gametype_guid}/{activity.map_guid}-{activity.id}">
                <strong>{activity.title}</strong> - {activity.game}

                <span class="info">
                    <!-- LOOP activity.type -->
                    <span class={activity.type.class}>{activity.type.n}</span>
                    <!-- END activity.type -->
                </span>
            </a>
        </li>
        <!-- END activity -->
    </ul>

