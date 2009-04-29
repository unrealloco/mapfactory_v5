
    <a name="commentList"></a>

        <p class="resultInfo">comments {result_from}-{result_to} of {result_total}</p>

    <ul class="comments">
        <!-- LOOP comment -->
        <li class="comment">
            <strong>{comment.name}</strong> <span>{comment.time}</span>
            <p>{comment.message}</p>
            <ul class="repllies">
                <!-- LOOP comment.response -->
                <li class="repply">
                    <strong>{comment.response.name}</strong><span>{comment.response.time}</span>
                    <p>{comment.response.message}</p>
                </li>
                <!-- END comment.response -->
            </ul>
            <em name="{comment.id}">>> Reply</em>
        </li>
        <!-- END comment -->
    </ul>

    <!-- SECTION pagination -->
    <div class="pagination">
        <!-- SECTION pagination_next -->
        <span onclick="javascript:commentShowPage({pagination_next});" class="next">next >></span>
        <!-- END pagination_next -->

        <!-- SECTION pagination_prev -->
        <span onclick="javascript:commentShowPage({pagination_prev});" class="prev"><< prev</span>
        <!-- END pagination_prev -->

        <ul>
            <!-- LOOP pagination_1 -->
            <li class="{pagination_1.class}"><span onclick="javascript:commentShowPage({pagination_1.link});">{pagination_1.n}</span></li>
            <!-- END pagination_1 -->

            <!-- SECTION pagination_space1 -->
            <li>...</li>
            <!-- END pagination_space1 -->

            <!-- LOOP pagination_2 -->
            <li class="{pagination_2.class}"><span onclick="javascript:commentShowPage({pagination_2.link});">{pagination_2.n}</span></li>
            <!-- END pagination_2 -->

            <!-- SECTION pagination_space2 -->
            <li>...</li>
            <!-- END pagination_space2 -->

            <!-- LOOP pagination_3 -->
            <li class="{pagination_3.class}"><span onclick="javascript:commentShowPage({pagination_3.link});">{pagination_3.n}</span></li>
            <!-- END pagination_3 -->
        </ul>
    </div>
    <!-- END pagination -->

