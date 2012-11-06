<aside class="sidebar article-sidebar left">
    <section class=article-index>
        <h6>CONTENTS</h6>
        <ol>
            <li>1) Introduction</li>
            <li>2) Scorecard</li>
            <li>3) Resources</li>
            <li>4) Comments</li>
            <li>5) Bibliography/Links</li>
        </ol>
    </section>

    <section class=your-scorecard>
        <h6 class=padded-title>YOUR SCORECARD</h6>
        <form action="" method=post class="custom score-form">
            <p class=clearfix>
                <label for=gw class=left>GW Bush</label>
                <select name="grade[]" id=grade class=left>
                    <option value="">?</option>
                    <option value="A+">A+</option>
                    <option value=A>A</option>
                    <option value=A->A-</option>
                    <option value="B+">B+</option>
                    <option value=B>B</option>
                    <option value=B->B-</option>
                    <option value="C+">C+</option>
                    <option value=C>C</option>
                    <option value=C->C-</option>
                    <option value="D+">D+</option>
                    <option value=D>D</option>
                    <option value=D->D-</option>
                    <option value=F>F</option>
                </select>
                <textarea name=gw id=gw class=left placeholder=Explanation></textarea>
            </p>
            <p class=clearfix>
                <label for=dk class=left>Dukakis</label>
                <select name="grade[]" id=grade class=left>
                    <option value="">?</option>
                    <option value="A+">A+</option>
                    <option value=A>A</option>
                    <option value=A->A-</option>
                    <option value="B+">B+</option>
                    <option value=B>B</option>
                    <option value=B->B-</option>
                    <option value="C+">C+</option>
                    <option value=C>C</option>
                    <option value=C->C-</option>
                    <option value="D+">D+</option>
                    <option value=D>D</option>
                    <option value=D->D-</option>
                    <option value=F>F</option>
                </select>
                <textarea name=dk id=dk class=left placeholder=Explanation></textarea>
            </p>
            <p class=clearfix>
                <label for=at class=left>Lee Atwater</label>
                <select name="grade[]" id=grade class=left>
                    <option value="">?</option>
                    <option value="A+">A+</option>
                    <option value=A>A</option>
                    <option value=A->A-</option>
                    <option value="B+">B+</option>
                    <option value=B>B</option>
                    <option value=B->B-</option>
                    <option value="C+">C+</option>
                    <option value=C>C</option>
                    <option value=C->C-</option>
                    <option value="D+">D+</option>
                    <option value=D>D</option>
                    <option value=D->D-</option>
                    <option value=F>F</option>
                </select>
                <textarea name=at id=at class=left placeholder=Explanation></textarea>
            </p>
            <p class=clearfix>
                <label for=hr class=left>Willie Horton</label>
                <select name="grade[]" id=grade class=left>
                    <option value="">?</option>
                    <option value="A+">A+</option>
                    <option value=A>A</option>
                    <option value=A->A-</option>
                    <option value="B+">B+</option>
                    <option value=B>B</option>
                    <option value=B->B-</option>
                    <option value="C+">C+</option>
                    <option value=C>C</option>
                    <option value=C->C-</option>
                    <option value="D+">D+</option>
                    <option value=D>D</option>
                    <option value=D->D-</option>
                    <option value=F>F</option>
                </select>
                <textarea name=hr id=hr class=left placeholder=Explanation></textarea>
            </p>
            <p class=clearfix>
                <label for=ap class=left>American Public</label>
                <select name="grade[]" id=grade class=left>
                    <option value="">?</option>
                    <option value="A+">A+</option>
                    <option value=A>A</option>
                    <option value=A->A-</option>
                    <option value="B+">B+</option>
                    <option value=B>B</option>
                    <option value=B->B-</option>
                    <option value="C+">C+</option>
                    <option value=C>C</option>
                    <option value=C->C-</option>
                    <option value="D+">D+</option>
                    <option value=D>D</option>
                    <option value=D->D-</option>
                    <option value=F>F</option>
                </select>
                <textarea name=ap id=ap class=left placeholder=Explanation></textarea>
            </p>
            <p class="controls no-bottom clearfix">
                <button id=expand-scoreboard class="expand left">+ expand</button>
                <input type=submit value=Submit class="green-btn right">
                <?php if (!is_user_logged_in()) : ?>
                <span class="not-logged clear right">(not logged in)</span>
                <?php endif; ?>
            </p>
        </form>
    </section>
</aside>