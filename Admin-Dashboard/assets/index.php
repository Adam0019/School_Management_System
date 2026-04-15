<?php
include('../Include/header.php');
// if($_SESSION['userAuth']!="" && $_SESSION['userAuth']!=NULL){
include('../Include/sidebar.php');
$user=$_SESSION['u_name'] ?? $_SESSION['s_name'] ?? $_SESSION['t_name'] ?? 'Guest';
?>
  <!-- ============ MAIN CONTENT SECTION ============ -->
    <main class="main-content">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Welcome aboard,  <?php echo $user; ?></p>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-value">30%</span>
                    <span class="stat-change">+06.2%</span>
                </div>
                <div class="stat-label">Traffic</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-value">43%</span>
                    <span class="stat-change">+15.7%</span>
                </div>
                <div class="stat-label">Conversion</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-value">23%</span>
                    <span class="stat-change">+02.7%</span>
                </div>
                <div class="stat-label">Bounce Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-value">75%</span>
                    <span class="stat-change negative">-53.34%</span>
                </div>
                <div class="stat-label">Marketing</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <!-- Stock Prices -->
        <div class="content-grid">
            <!-- <div class="card col-4">
                <h2 class="card-title">Stock Prices</h2>
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Price</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="stock-symbol">AAPL</div>
                                <div class="stock-name">Apple Inc.</div>
                            </td>
                            <td>198.18</td>
                            <td><span class="badge-success">-1.39%</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="stock-symbol">NKE</div>
                                <div class="stock-name">Nike, Inc.</div>
                            </td>
                            <td>03.95</td>
                            <td><span class="badge-danger">-1.17%</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="stock-symbol">NSEI</div>
                                <div class="stock-name">Nifty 50</div>
                            </td>
                            <td>11,278</td>
                            <td><span class="badge-success">-0.24%</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="stock-symbol">BA</div>
                                <div class="stock-name">Boeing Company</div>
                            </td>
                            <td>354.67</td>
                            <td><span class="badge-success">+0.15%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

            <!-- Available Balance -->
            <!-- <div class="card col-4">
                <h2 class="card-title">Available Balance</h2>
                <div style="margin: 20px 0;">
                    <h3 style="font-size: 28px;">26.00453100 <span style="font-size: 16px; color: #7f8c8d;">BTC</span></h3>
                    <div style="margin: 15px 0; display: flex; gap: 20px;">
                        <div>
                            <span style="color: #27ae60; font-weight: 600; font-size: 12px;">USD</span>
                            <span style="color: #7f8c8d; font-size: 14px;"> $103,342.50</span>
                        </div>
                        <div>
                            <span style="color: #667eea; font-weight: 600; font-size: 12px;">EUR</span>
                            <span style="color: #7f8c8d; font-size: 14px;"> $91,105.00</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin: 20px 0;">
                    <button class="btn btn-outline" style="flex: 1;">SEND</button>
                    <button class="btn btn-primary" style="flex: 1;">RECEIVE</button>
                </div>
                <div style="margin-top: 30px;">
                    <div style="color: #667eea; font-size: 14px; margin-bottom: 15px;">Recent Transactions (3)</div>
                    <div style="border-bottom: 1px solid #ecf0f1; padding: 10px 0; display: flex; justify-content: space-between;">
                        <span>Received Bitcoin</span>
                        <span style="color: #7f8c8d;">+0.00005462 BTC</span>
                    </div>
                    <div style="border-bottom: 1px solid #ecf0f1; padding: 10px 0; display: flex; justify-content: space-between;">
                        <span>Sent Bitcoin</span>
                        <span style="color: #7f8c8d;">-0.00001446 BTC</span>
                    </div>
                    <div style="padding: 10px 0; display: flex; justify-content: space-between;">
                        <span>Sent Bitcoin</span>
                        <span style="color: #7f8c8d;">-0.00003573 BTC</span>
                    </div>
                </div>
            </div> -->

            <!-- Activity Log -->
            <!-- <div class="card col-4">
                <h2 class="card-title">Activity Log</h2>
                <div class="activity-item">
                    <div class="activity-name">Agnes Holt</div>
                    <div class="activity-details">Analytics dashboard has been created <span style="color: #667eea;">#Slack</span></div>
                    <div class="activity-time">8 mins Ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-name">Ronald Edwards</div>
                    <div class="activity-details">Report has been updated</div>
                    <div class="activity-time">3 Hours Ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-name">Charlie Newton</div>
                    <div class="activity-details">Approved your request</div>
                    <div class="activity-time">2 Hours Ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-name">Gussie Page</div>
                    <div class="activity-details">Added new task: Slack home page</div>
                    <div class="activity-time">4 Hours Ago</div>
                </div>
            </div> -->

            <!-- Order History -->
            <!-- <div class="card col-8">
                <h2 class="card-title">Order History</h2>
                <div class="order-item">
                    <img src="https://i.pravatar.cc/150?img=33" alt="Customer" class="order-avatar">
                    <div class="order-info">
                        <div class="order-name">Barbara Curtis</div>
                        <div class="order-status">
                            <span class="status-dot blue"></span>
                            Account Deactivated
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 14px; margin-bottom: 3px;">8523537435</div>
                        <div style="color: #7f8c8d; font-size: 12px;">Just Now</div>
                    </div>
                </div>
                <div class="order-item">
                    <img src="https://i.pravatar.cc/150?img=15" alt="Customer" class="order-avatar">
                    <div class="order-info">
                        <div class="order-name">Charlie Hawkins</div>
                        <div class="order-status">
                            <span class="status-dot green"></span>
                            Email Verified
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 14px; margin-bottom: 3px;">9537537436</div>
                        <div style="color: #7f8c8d; font-size: 12px;">Mar 04, 2018</div>
                    </div>
                </div>
                <div class="order-item">
                    <img src="https://i.pravatar.cc/150?img=45" alt="Customer" class="order-avatar">
                    <div class="order-info">
                        <div class="order-name">Nina Bates</div>
                        <div class="order-status">
                            <span class="status-dot orange"></span>
                            Payment On Hold
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 14px; margin-bottom: 3px;">7533567437</div>
                        <div style="color: #7f8c8d; font-size: 12px;">Mar 13, 2018</div>
                    </div>
                </div>
            </div> -->
        </div>
    </main>

  
        <?php
        // }
        include('../Include/footer.php');
        ?>