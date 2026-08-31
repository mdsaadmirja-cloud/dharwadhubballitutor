<?php
require "../../Admin/session.php";
require "../../Admin/Utilities/Helper.php";
include "../../Admin/DB Operations/followupOps.php";
require "../../Model/Registration.php";
require_once "header.php";
$id = $_GET["id"];
?>
<div class="card">
    <div class="card-header">
        <h6>Follow Up's</h6>
    </div>
    <div class=card-body>
        <form method="post" id="followup_form" action="../Controller/newfollowup.php">
            <table class="table table-bordered" id="followuptable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Follwed By</th>
                        <th>FollowUp Date</th>
                        <th>Comments</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <?php
                $followUpList = DBfollow::getFollowUpByEnqId($id);
                foreach ($followUpList as $follow) {
                    echo "<tr><td> "  . $follow->get_followupBy() . "</td><td>" . $follow->get_followupDate() . "</td><td>" . $follow->get_followcomment() . "</td><td>" . $follow->get_status() . "</td><td>" . $follow->get_followupOn() . '</td></tr>';
                }
                ?>
            </table>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="followupDate" class="col-md-6 control-label">FollowUp Date</label>
                        <div class="col-sm-12">
                            <input type="date" id="followupDate" name="followupDate" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="col-md-6 control-label">Status</label>
                        <div class="col-sm-12">
                            <select class="custom-select" id="status" name="status">
                                <option value="">Select</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Converted">Converted</option>
                                <option value="Bad">Bad</option>
                                <option value="Demo Class">Demo Class</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <legend>Comments:</legend>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="followcomment" style="height: 100px" data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-trigger="keyup" name="followcomment"></textarea>
                                <label for="followcomment">Comments</label>
                            </div>
                            <input type="hidden" name="followenqid" id="followenqid" value="<?php echo $id; ?>">
                            <fieldset>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-8">
                        <input type="hidden" name="followupBy" id="followupBy" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" value=<?php echo $_SESSION['login_user']; ?> />
                    </div>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-warning">FollowUp</button>
    </div>
</div>

</form>
</div>
</div>
</div>
</div>
</body>
<script>
    
    ($document).ready(function() {
        $("#followupDate").focus(function() {
            let thisYear = new Date();
            thisYear = thisYear.getFullYear();
            let allowedYear = thisYear - 5;
            allowedYear = allowedYear.toString();
            let year = new Date(allowedYear);
            let dd = String(year.getDate()).padStart(2, '0');
            let mm = String(year.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = year.getFullYear();
            year = yyyy + '-' + mm + '-' + dd;
            $("#followupDate").attr("max", year);
        })

    });
</script>

</html>