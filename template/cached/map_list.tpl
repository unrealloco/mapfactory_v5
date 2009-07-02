
    <h1>{page_title}</h1>

    <!-- SECTION sortBy -->
    <ul class="sortBy">
        <li>Sort by : </li>
        <li class="{sortBy_active_date}"><a href="{ROOT_PATH}{currentURI}{sortBy_url_none}">date</a> - </li>
        <li class="{sortBy_active_commented}"><a href="{ROOT_PATH}{currentURI}?sortby=commented{limitToParameter}">comment</a> - </li>
        <li class="{sortBy_active_rated}"><a href="{ROOT_PATH}{currentURI}?sortby=rated{limitToParameter}">rating</a> - </li>
        <li class="{sortBy_active_downloaded}"><a href="{ROOT_PATH}{currentURI}?sortby=downloaded{limitToParameter}">download</a></li>
    </ul>
    <!-- END sortBy -->

    <!-- SECTION limitTo -->
    <ul class="limitTo">
        <li>Limit to : </li>
        <li class="{limitTo_active_none}"><a href="{ROOT_PATH}{currentURI}{limitTo_url_none}">all time</a> - </li>
        <li class="{limitTo_active_month}"><a href="{ROOT_PATH}{currentURI}?{sortByParameter}limitto=1month">last month</a> - </li>
        <li class="{limitTo_active_3month}"><a href="{ROOT_PATH}{currentURI}?{sortByParameter}limitto=3month">last 3 months</a> - </li>
        <li class="{limitTo_active_3month}"><a href="{ROOT_PATH}{currentURI}?{sortByParameter}limitto=1year">last year</a></li>
    </ul>
    <!-- END limitTo -->

    <!-- SECTION result_info -->
    <p class="resultInfo">maps {result_from}-{result_to} of {result_total}</p>
    <!-- END result_info -->

    <ul class="maplist">
        <!-- LOOP map -->
        <li class="line {map.class}">
            <span class="info">
                <span class="star" title="rating : {map.rattingPercent}/100"><span style="width:{map.ratting}px;"></span></span>
                <a href="{ROOT_PATH}{map.game_guid}/{map.gametype_guid}/{map.map_guid}-{map.id}#comment" class="preview">
                    {map.comment} comment{map.comment_s}
                </a>
                | {map.download} download{map.download_s} |
            </span>

            <a href="{ROOT_PATH}{map.game_guid}/{map.gametype_guid}/{map.map_guid}-{map.id}" class="preview">
                <img src="{ROOT_PATH}screenshot/80x60/{map.map_guid}-{map.image}.jpg" width="80px" height="60px" alt="{map.title}" title="{map.game} {map.title}">
            </a>

            <ul>
                <li>
                    <a href="{ROOT_PATH}{map.game_guid}/{map.gametype_guid}/{map.map_guid}-{map.id}" alt="{map.title}" title="{map.game} {map.title}">
                        <strong>{map.title}</strong>
                    </a>
                </li>
                <li>
                    <a href="{ROOT_PATH}{map.game_guid}" alt="{map.game}" title="{map.game} custom maps">{map.game}</a>
                    <span>-</span>
                    <a href="{ROOT_PATH}{map.game_guid}/{map.gametype_guid}" alt="{map.gametype}" title="{map.game} {map.gametype}">{map.gametype}</a>
                </li>
                <li>
                    <span>realised by</span>
                    <a href="{ROOT_PATH}author/{map.author_guid}-{map.author_id}" alt="{map.author}" title="{map.author}'s maps">{map.author}</a>
                </li>
            </ul>
        </li>
        <!-- END map -->
    </ul>

    <!-- SECTION noResult -->
    <div class="niceText" id="noResult">
        <p>Your search was: <i>{search_path}</i></p>
        <p>Try fewer or more general keywords.</p>
        <!-- SECTION noResult_tip1 -->
        <p><b>TIPS :</b> <a href="{ROOT_PATH}search/{search_query}">Look for <b>"{search_query}"</b> in the whole site ...</a></p>
        <!-- END noResult_tip1 -->
    </div>
    <!-- END noResult -->

    <div class="pagination">
        <!-- SECTION pagination -->
        <!-- SECTION pagination_next -->
        <a href="{ROOT_PATH}{pagination_next}" class="next">next >></a>
        <!-- END pagination_next -->

        <!-- SECTION pagination_prev -->
        <a href="{ROOT_PATH}{pagination_prev}" class="prev"><< prev</a>
        <!-- END pagination_prev -->

        <ul>
            <!-- LOOP pagination_1 -->
            <li class="{pagination_1.class}"><a href="{ROOT_PATH}{pagination_1.link}">{pagination_1.n}</a></li>
            <!-- END pagination_1 -->

            <!-- SECTION pagination_space1 -->
            <li><span>...</span></li>
            <!-- END pagination_space1 -->

            <!-- LOOP pagination_2 -->
            <li class="{pagination_2.class}"><a href="{ROOT_PATH}{pagination_2.link}">{pagination_2.n}</a></li>
            <!-- END pagination_2 -->

            <!-- SECTION pagination_space2 -->
            <li><span>...</span></li>
            <!-- END pagination_space2 -->

            <!-- LOOP pagination_3 -->
            <li class="{pagination_3.class}"><a href="{ROOT_PATH}{pagination_3.link}">{pagination_3.n}</a></li>
            <!-- END pagination_3 -->
        </ul>
        <!-- END pagination -->
    </div>

