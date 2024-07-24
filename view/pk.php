<style>
    .hidden {
        display: none;
    }
</style>

<div>
    <h1>Pohon Keputusan</h1>
    <div class="card-home">
        <div>
            <a href='#stepTree' onclick="showContent('stepTree')" class='button-mining'>Step Tree</a>
            Dari <span style="display: inline; font-size: 2em; font-weight: bold; margin: 0;">70%</span> data
        </div>
    </div>
    <div id="stepTree" class="hidden">
        <div class="card-home" style="color: black;">
            <p>Ini adalah konten untuk Step Tree.</p>
            <?php
            if (isset($_SESSION['decision_tree'])) {
                $decision_tree = $_SESSION['decision_tree'];

                function getRules($tree, $currentRule = [])
                {
                    $rules = [];
                    if (is_array($tree)) {
                        foreach ($tree as $attribute => $branches) {
                            foreach ($branches as $value => $subtree) {
                                $newRule = $currentRule;
                                $newRule[$attribute] = $value;
                                if (is_array($subtree)) {
                                    $rules = array_merge($rules, getRules($subtree, $newRule));
                                } else {
                                    $newRule['Status Lulus'] = $subtree;
                                    $rules[] = $newRule;
                                }
                            }
                        }
                    }
                    return $rules;
                }

                $rules = getRules($decision_tree);
            }
            ?>
            <div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rule</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rules)) : ?>
                            <?php foreach ($rules as $index => $rule) : ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <?php
                                        $ruleStr = [];
                                        foreach ($rule as $key => $value) {
                                            if ($key !== 'Status Lulus') {
                                                $ruleStr[] = "$key = $value";
                                            }
                                        }
                                        echo implode(', ', $ruleStr);
                                        echo " -> " . $rule['Status Lulus'];
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2">Tidak ada aturan yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>