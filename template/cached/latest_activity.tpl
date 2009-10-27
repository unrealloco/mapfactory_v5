
    <h3>Recent activity</h3>

    <ul class="block activity" id="latestActivity">
        <!-- LOOP activity -->
        <li class="map" name="{activity.image}">
            <a href="{ROOT_PATH}{activity.game_guid}/{activity.gametype_guid}/{activity.map_guid}-{activity.id}">
                <strong>{activity.title}</strong>

                <!-- LOOP activity.type -->
                <span class="{activity.type.class} info">{activity.type.n}</span>
                <!-- END activity.type -->

                <span class="detail">{activity.game} - {activity.gametype}</span>
            </a>
        </li>
        <!-- END activity -->
    </ul>

    <div id="activityPreview"></div>

