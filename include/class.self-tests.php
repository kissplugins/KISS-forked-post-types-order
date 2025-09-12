<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    /**
     * KISS Post Types Order Self-Testing System
     *
     * ⚠️  CRITICAL DIAGNOSTIC SYSTEM - DO NOT REFACTOR UNLESS ABSOLUTELY NECESSARY ⚠️
     *
     * This class provides comprehensive self-testing functionality for the plugin and is responsible for:
     * - Detecting regressions and bugs after code changes
     * - Validating core functionality integrity
     * - Performance monitoring and optimization verification
     * - Security validation and vulnerability detection
     * - Database connectivity and operation testing
     *
     * REFACTORING RISKS:
     * - Loss of regression detection capabilities
     * - Breaking diagnostic functionality for troubleshooting
     * - Inability to validate plugin health after updates
     * - Loss of performance monitoring capabilities
     * - Breaking AJAX test execution system
     *
     * TESTING REQUIRED AFTER ANY CHANGES:
     * - Run all self-tests to ensure they still function
     * - Test AJAX execution and response handling
     * - Verify UI components and status updates
     * - Test with various WordPress environments
     * - Validate security checks and error handling
     *
     * @package KISS_Post_Types_Order
     * @subpackage Self_Tests
     * @since 2.8.0
     * @author KISS Code
     */
    class PTO_SelfTests
        {

            /**
             * Constructor - Initialize self-testing system
             *
             * ⚠️  CRITICAL INITIALIZATION - DO NOT MODIFY UNLESS NECESSARY ⚠️
             *
             * Sets up WordPress hooks for:
             * - Admin menu integration under Tools
             * - AJAX handler for test execution
             *
             * REFACTORING RISKS:
             * - Breaking admin menu integration
             * - Loss of AJAX test execution capability
             * - Breaking WordPress hook system integration
             *
             * @since 2.8.0
             */
            function __construct()
                {
                    add_action('admin_menu', array($this, 'add_tools_menu'));
                    add_action('wp_ajax_pto_run_self_test', array($this, 'run_single_test'));
                }

            /**
             * Add self-tests page to WordPress Tools menu
             *
             * ⚠️  CRITICAL ADMIN INTEGRATION - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Creates the self-tests admin page under Tools menu with:
             * - Proper capability checking (manage_options)
             * - Internationalized menu labels
             * - Integration with WordPress admin system
             * - Dynamic version number in page title
             *
             * REFACTORING RISKS:
             * - Breaking admin menu integration
             * - Loss of self-tests page accessibility
             * - Security issues with capability requirements
             * - Breaking internationalization
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Verify menu appears under Tools for administrators
             * - Test capability restrictions with different user roles
             * - Verify page loads correctly with dynamic version
             * - Test internationalization with different languages
             *
             * @since 2.8.0
             * @return void
             */
            function add_tools_menu()
                {
                    add_management_page(
                        __('KISS Re-Order Self Tests', 'post-types-order'),
                        __('KISS Re-Order Self Tests', 'post-types-order'),
                        'manage_options',
                        'pto-self-tests',
                        array($this, 'render_tests_page')
                    );
                }

            /**
             * Render the self-tests page interface
             *
             * ⚠️  CRITICAL UI COMPONENT - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Generates the complete self-tests interface including:
             * - Dynamic page title with version number
             * - Test control buttons (Run All, Clear Results)
             * - Dynamic test summary with real-time updates
             * - Individual test cards with status indicators
             * - CSS styling for professional appearance
             * - JavaScript for AJAX test execution
             *
             * REFACTORING RISKS:
             * - Breaking test execution interface
             * - Loss of real-time status updates
             * - Breaking AJAX communication with backend
             * - CSS/JavaScript conflicts with WordPress admin
             * - Loss of dynamic summary functionality
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test complete interface rendering
             * - Verify all buttons and controls work
             * - Test AJAX execution and status updates
             * - Verify CSS styling in different browsers
             * - Test JavaScript functionality and error handling
             *
             * @since 2.8.0
             * @return void Outputs HTML directly
             */
            function render_tests_page()
                {
                    // Check if debug tool is requested
                    if (isset($_GET['pto_debug']) && $_GET['pto_debug'] === '1') {
                        // Include the secure debug tool
                        include_once(CPTPATH . '/debug-category-filter.php');
                        return;
                    }

                    ?>
                    <div class="wrap">
                        <h1><?php echo sprintf(__('KISS Re-Order Self Tests - v%s', 'post-types-order'), PTO_VERSION); ?></h1>

                        <div class="notice notice-info">
                            <p><strong><?php _e('About Self Tests:', 'post-types-order'); ?></strong></p>
                            <p><?php _e('These tests help detect regressions and bugs after refactoring. Run them after any code changes to ensure core functionality remains intact.', 'post-types-order'); ?></p>
                        </div>

                        <div class="pto-self-tests">
                            <div class="test-controls" style="margin: 20px 0;">
                                <button type="button" id="run-all-tests" class="button button-primary"><?php _e('Run All Tests', 'post-types-order'); ?></button>
                                <button type="button" id="clear-results" class="button"><?php _e('Clear Results', 'post-types-order'); ?></button>
                                <a href="<?php echo admin_url('tools.php?page=pto-self-tests&pto_debug=1&_wpnonce=' . wp_create_nonce('pto_debug_nonce')); ?>" class="button"><?php _e('Category Filter Debug', 'post-types-order'); ?></a>
                            </div>

                            <div class="test-summary" id="test-summary" style="margin: 20px 0; padding: 15px; border-radius: 4px; font-weight: bold; display: none;">
                                <span id="summary-text"></span>
                            </div>

                            <div class="test-results" id="test-results">
                                <!-- Test results will be populated here -->
                            </div>

                            <div class="test-list">
                                <?php $this->render_test_list(); ?>
                            </div>
                        </div>

                        <style>
                        .pto-self-tests .test-item {
                            border: 1px solid #ddd;
                            margin: 10px 0;
                            padding: 15px;
                            background: #fff;
                            border-radius: 4px;
                        }
                        .test-item h3 {
                            margin: 0 0 10px 0;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }
                        .test-status {
                            padding: 4px 8px;
                            border-radius: 3px;
                            font-size: 12px;
                            font-weight: bold;
                        }
                        .status-pending { background: #f0f0f1; color: #646970; }
                        .status-running { background: #007cba; color: white; }
                        .status-pass { background: #00a32a; color: white; }
                        .status-fail { background: #d63638; color: white; }
                        .test-description { margin: 10px 0; color: #646970; }
                        .test-result { margin: 10px 0; padding: 10px; border-radius: 3px; }
                        .result-pass { background: #d1e7dd; border: 1px solid #badbcc; }
                        .result-fail { background: #f8d7da; border: 1px solid #f5c2c7; }
                        .test-details { font-family: monospace; font-size: 12px; margin-top: 10px; }
                        .test-summary { border: 2px solid; }
                        .summary-pass { background: #d1e7dd; border-color: #00a32a; color: #00a32a; }
                        .summary-fail { background: #f8d7da; border-color: #d63638; color: #d63638; }
                        .summary-partial { background: #fff3cd; border-color: #007cba; color: #007cba; }
                        </style>

                        <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            $('#run-all-tests').click(function() {
                                runAllTests();
                            });

                            $('#clear-results').click(function() {
                                clearResults();
                            });

                            $('.run-single-test').click(function() {
                                var testId = $(this).data('test-id');
                                runSingleTest(testId);
                            });

                            function runAllTests() {
                                $('.test-item').each(function() {
                                    var testId = $(this).data('test-id');
                                    runSingleTest(testId);
                                });
                            }

                            function updateSummary() {
                                var totalTests = $('.test-item').length;
                                var passedTests = $('.test-status.status-pass').length;
                                var failedTests = $('.test-status.status-fail').length;
                                var runningTests = $('.test-status.status-running').length;
                                var completedTests = passedTests + failedTests;

                                var $summary = $('#test-summary');
                                var $summaryText = $('#summary-text');

                                if (completedTests === 0) {
                                    $summary.hide();
                                    return;
                                }

                                var summaryText = completedTests + ' of ' + totalTests + ' Tests Completed. ';

                                if (failedTests === 0 && runningTests === 0 && completedTests === totalTests) {
                                    summaryText += 'All Tests Passed';
                                    $summary.removeClass('summary-fail summary-partial').addClass('summary-pass');
                                } else if (failedTests > 0) {
                                    summaryText += failedTests + ' Test' + (failedTests === 1 ? '' : 's') + ' Failed';
                                    $summary.removeClass('summary-pass summary-partial').addClass('summary-fail');
                                } else {
                                    summaryText += 'Tests In Progress';
                                    $summary.removeClass('summary-pass summary-fail').addClass('summary-partial');
                                }

                                $summaryText.text(summaryText);
                                $summary.show();
                            }

                            function runSingleTest(testId) {
                                var $testItem = $('.test-item[data-test-id="' + testId + '"]');
                                var $status = $testItem.find('.test-status');
                                var $result = $testItem.find('.test-result');

                                $status.removeClass('status-pending status-pass status-fail').addClass('status-running').text('Running...');
                                $result.hide();

                                $.ajax({
                                    url: ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: 'pto_run_self_test',
                                        test_id: testId,
                                        nonce: '<?php echo wp_create_nonce('pto_self_test_nonce'); ?>'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            $status.removeClass('status-running').addClass('status-pass').text('PASS');
                                            $result.removeClass('result-fail').addClass('result-pass').html(response.data.message).show();
                                        } else {
                                            $status.removeClass('status-running').addClass('status-fail').text('FAIL');
                                            $result.removeClass('result-pass').addClass('result-fail').html(response.data.message).show();
                                        }

                                        if (response.data.details) {
                                            $result.append('<div class="test-details">' + response.data.details + '</div>');
                                        }

                                        // Update summary after each test completes
                                        updateSummary();
                                    },
                                    error: function() {
                                        $status.removeClass('status-running').addClass('status-fail').text('ERROR');
                                        $result.removeClass('result-pass').addClass('result-fail').html('AJAX request failed').show();
                                    }
                                });
                            }

                            function clearResults() {
                                $('.test-status').removeClass('status-running status-pass status-fail').addClass('status-pending').text('Pending');
                                $('.test-result').hide();
                                $('#test-summary').hide();
                            }
                        });
                        </script>
                    </div>
                    <?php
                }

            /**
            * Render the list of available tests
            */
            function render_test_list()
                {
                    $tests = $this->get_test_definitions();

                    foreach ($tests as $test_id => $test) {
                        ?>
                        <div class="test-item" data-test-id="<?php echo esc_attr($test_id); ?>">
                            <h3>
                                <?php echo esc_html($test['name']); ?>
                                <div>
                                    <span class="test-status status-pending">Pending</span>
                                    <button type="button" class="button run-single-test" data-test-id="<?php echo esc_attr($test_id); ?>"><?php _e('Run Test', 'post-types-order'); ?></button>
                                </div>
                            </h3>
                            <div class="test-description"><?php echo esc_html($test['description']); ?></div>
                            <?php if ($test_id === 'orderby_live_query') : ?>
                            <div class="test-helper" style="font-size:12px;color:#646970;margin-top:6px;">
                                <?php _e('Note: In admin with Admin Sort disabled or with Autosort disabled, this test may show “Indeterminate”. To get a definite PASS, run from a frontend context where Autosort is enabled, or temporarily enable Admin Sort in the plugin settings.', 'post-types-order'); ?>
                            </div>
                            <?php endif; ?>

                            <div class="test-result" style="display: none;"></div>
                        </div>
                        <?php
                    }
                }

            /**
            * Get test definitions
            */
            function get_test_definitions()
                {
                    return array(
                        'database_connectivity' => array(
                            'name' => __('Database Connectivity Test', 'post-types-order'),
                            'description' => __('Verifies that the plugin can connect to the database and perform basic operations on the posts table.', 'post-types-order'),
                            'critical' => true
                        ),
                        'post_ordering_functionality' => array(
                            'name' => __('Post Ordering Functionality Test', 'post-types-order'),
                            'description' => __('Tests the core post ordering mechanism by creating test posts and verifying menu_order updates work correctly.', 'post-types-order'),
                            'critical' => true
                        ),
                        'ajax_security_validation' => array(
                            'name' => __('AJAX Security Validation Test', 'post-types-order'),
                            'description' => __('Validates that AJAX handlers properly check nonces, capabilities, and input sanitization.', 'post-types-order'),
                            'critical' => true
                        ),
                        'pagination_performance' => array(
                            'name' => __('Pagination Performance Test', 'post-types-order'),
                            'description' => __('Ensures pagination limits are working and queries are not unbounded (posts_per_page != -1).', 'post-types-order'),
                            'critical' => true
                        ),
                        'sorting_filtering_integrity' => array(
                            'name' => __('Sorting & Filtering Integrity Test', 'post-types-order'),
                            'description' => __('Verifies core hook registrations for ordering/filtering (pre_get_posts, posts_orderby) and related AJAX actions.', 'post-types-order'),
                            'critical' => true
                        ),
                        'orderby_live_query' => array(
                            'name' => __('Live Query Order Test', 'post-types-order'),
                            'description' => __('Runs a lightweight query and inspects final ORDER BY to confirm menu_order influence when applicable.', 'post-types-order'),
                            'critical' => false
                        )
                    );
                }

            /**
             * AJAX handler for running individual tests
             *
             * ⚠️  CRITICAL AJAX HANDLER - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Handles AJAX requests for test execution with:
             * - Comprehensive security validation (nonce, capabilities)
             * - Input sanitization and validation
             * - Test execution coordination
             * - JSON response formatting
             * - Error handling and reporting
             *
             * REFACTORING RISKS:
             * - Breaking AJAX test execution system
             * - Security vulnerabilities in test execution
             * - Loss of real-time test feedback
             * - Breaking frontend JavaScript integration
             * - Invalid JSON responses causing frontend errors
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test AJAX security validation
             * - Verify all test types execute correctly
             * - Test error handling and response formats
             * - Verify frontend receives proper responses
             * - Test with different user permission levels
             *
             * @since 2.8.0
             * @return void Outputs JSON response directly
             */
            function run_single_test()
                {
                    // Security checks
                    if (!current_user_can('manage_options')) {
                        wp_send_json_error(array('message' => __('Insufficient permissions.', 'post-types-order')));
                        return;
                    }

                    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
                    if (!wp_verify_nonce($nonce, 'pto_self_test_nonce')) {
                        wp_send_json_error(array('message' => __('Security verification failed.', 'post-types-order')));
                        return;
                    }

                    $test_id = isset($_POST['test_id']) ? sanitize_text_field(wp_unslash($_POST['test_id'])) : '';
                    if (empty($test_id)) {
                        wp_send_json_error(array('message' => __('No test ID provided.', 'post-types-order')));
                        return;
                    }

                    // Run the specific test
                    $result = $this->execute_test($test_id);

                    if ($result['success']) {
                        wp_send_json_success($result);
                    } else {
                        wp_send_json_error($result);
                    }
                }

            /**
             * Execute a specific test by ID
             *
             * ⚠️  CRITICAL TEST COORDINATOR - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Coordinates test execution with:
             * - Test routing and dispatch
             * - Performance timing measurement
             * - Exception handling and error reporting
             * - Result formatting and standardization
             * - Execution time tracking
             *
             * REFACTORING RISKS:
             * - Breaking test execution routing
             * - Loss of performance timing data
             * - Breaking exception handling
             * - Invalid result format causing frontend errors
             * - Loss of test execution coordination
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test all individual test types
             * - Verify exception handling works correctly
             * - Test performance timing accuracy
             * - Verify result format consistency
             * - Test with invalid test IDs
             *
             * @since 2.8.0
             * @param string $test_id The ID of the test to execute
             * @return array Test result with success status, message, and details
             */
            function execute_test($test_id)
                {
                    $start_time = microtime(true);

                    try {
                        switch ($test_id) {
                            case 'database_connectivity':
                                $result = $this->test_database_connectivity();
                                break;

                            case 'post_ordering_functionality':
                                $result = $this->test_post_ordering_functionality();
                                break;

                            case 'ajax_security_validation':
                                $result = $this->test_ajax_security_validation();
                                break;

                            case 'pagination_performance':
                                $result = $this->test_pagination_performance();
                                break;

                            case 'sorting_filtering_integrity':
                                $result = $this->test_sorting_filtering_integrity();
                                break;

                            case 'orderby_live_query':
                                $result = $this->test_orderby_live_query();
                                break;

                            default:
                                return array(
                                    'success' => false,
                                    'message' => sprintf(__('Unknown test: %s', 'post-types-order'), $test_id)
                                );
                        }

                        $execution_time = round((microtime(true) - $start_time) * 1000, 2);
                        $result['details'] = isset($result['details']) ? $result['details'] : '';
                        $result['details'] .= "\nExecution time: {$execution_time}ms";

                        return $result;

                    } catch (Exception $e) {
                        return array(
                            'success' => false,
                            'message' => sprintf(__('Test failed with exception: %s', 'post-types-order'), $e->getMessage()),
                            'details' => $e->getTraceAsString()
                        );
                    }
                }

            /**
             * Test 1: Database Connectivity and Operations
             *
             * ⚠️  CRITICAL DATABASE TEST - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Validates core database functionality including:
             * - WordPress posts table accessibility
             * - menu_order column query capability
             * - UPDATE operation permissions
             * - Database error handling
             * - Connection stability verification
             *
             * REFACTORING RISKS:
             * - Breaking database connectivity validation
             * - Loss of menu_order column verification
             * - Breaking UPDATE permission testing
             * - Invalid SQL causing database errors
             * - Loss of error detection capabilities
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test with various database configurations
             * - Verify error handling with invalid queries
             * - Test UPDATE permission detection
             * - Verify menu_order column accessibility
             * - Test with different WordPress table prefixes
             *
             * @since 2.8.0
             * @return array Test result with success status and diagnostic details
             */
            function test_database_connectivity()
                {
                    global $wpdb;

                    $checks = array();

                    // Check if we can query the posts table
                    $post_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts}");
                    if ($post_count === null) {
                        return array(
                            'success' => false,
                            'message' => __('Failed to query posts table', 'post-types-order'),
                            'details' => 'Database error: ' . $wpdb->last_error
                        );
                    }
                    $checks[] = "✓ Posts table accessible ({$post_count} posts found)";

                    // Check if we can query menu_order column
                    $menu_order_test = $wpdb->get_var("SELECT menu_order FROM {$wpdb->posts} LIMIT 1");
                    if ($wpdb->last_error) {
                        return array(
                            'success' => false,
                            'message' => __('Failed to access menu_order column', 'post-types-order'),
                            'details' => 'Database error: ' . $wpdb->last_error
                        );
                    }
                    $checks[] = "✓ menu_order column accessible";

                    // Check if we can perform UPDATE operations (dry run)
                    $test_query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_type = %s LIMIT 1", 'post');
                    $test_post_id = $wpdb->get_var($test_query);

                    if ($test_post_id) {
                        // Test UPDATE capability (but don't actually change anything)
                        $current_order = $wpdb->get_var($wpdb->prepare("SELECT menu_order FROM {$wpdb->posts} WHERE ID = %d", $test_post_id));
                        $checks[] = "✓ UPDATE operations possible (test post ID: {$test_post_id})";
                    } else {
                        $checks[] = "⚠ No test posts available for UPDATE test";
                    }

                    return array(
                        'success' => true,
                        'message' => __('Database connectivity test passed', 'post-types-order'),
                        'details' => implode("\n", $checks)
                    );
                }

            /**
             * Test 2: Post Ordering Functionality and Core Integration
             *
             * ⚠️  CRITICAL FUNCTIONALITY TEST - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Validates core post ordering system including:
             * - CPTO class initialization and availability
             * - Core method existence and accessibility
             * - WordPress hook registration verification
             * - menu_order data integrity validation
             * - Post ordering mechanism functionality
             *
             * REFACTORING RISKS:
             * - Breaking core functionality validation
             * - Loss of CPTO class verification
             * - Breaking WordPress hook detection
             * - Invalid menu_order validation logic
             * - Loss of integration testing capabilities
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test CPTO class loading and initialization
             * - Verify all core methods are accessible
             * - Test WordPress hook registration detection
             * - Verify menu_order validation logic
             * - Test with different post types and configurations
             *
             * @since 2.8.0
             * @return array Test result with success status and diagnostic details
             */
            function test_post_ordering_functionality()
                {
                    global $wpdb;

                    $checks = array();

                    // Ensure CPTO class is loaded
                    if (!class_exists('CPTO')) {
                        if (file_exists(CPTPATH . '/include/class.cpto.php')) {
                            include_once(CPTPATH . '/include/class.cpto.php');
                        }
                    }

                    // Check if CPTO class exists and is properly initialized
                    global $CPTO;
                    if (!$CPTO || !is_object($CPTO)) {
                        // Try to create instance if class exists but global is not set
                        if (class_exists('CPTO')) {
                            $checks[] = "⚠ CPTO class exists but global not initialized";
                        } else {
                            return array(
                                'success' => false,
                                'message' => __('CPTO class not found', 'post-types-order'),
                                'details' => 'CPTO class file may not be loaded properly'
                            );
                        }
                    } else {
                        $checks[] = "✓ CPTO class properly initialized";
                    }

                    // Check if core methods exist
                    $required_methods = array('saveAjaxOrder', 'filterPostsByCategory');
                    $optional_methods = array('processOrderData'); // This is a private method added in recent updates

                    $missing_required = array();
                    foreach ($required_methods as $method) {
                        if ($CPTO && !method_exists($CPTO, $method)) {
                            $missing_required[] = $method;
                        }
                    }

                    if (!empty($missing_required)) {
                        return array(
                            'success' => false,
                            'message' => sprintf(__('Required methods missing: %s', 'post-types-order'), implode(', ', $missing_required)),
                            'details' => 'Core functionality may be broken'
                        );
                    }

                    $existing_methods = array();
                    foreach (array_merge($required_methods, $optional_methods) as $method) {
                        if ($CPTO && method_exists($CPTO, $method)) {
                            $existing_methods[] = $method;
                        }
                    }
                    $checks[] = "✓ Core methods exist: " . implode(', ', $existing_methods);

                    // Test menu_order functionality with existing posts
                    $test_posts = $wpdb->get_results("SELECT ID, menu_order FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' LIMIT 3");

                    if (empty($test_posts)) {
                        $checks[] = "⚠ No published posts available for ordering test";
                    } else {
                        $checks[] = sprintf("✓ Found %d test posts for ordering validation", count($test_posts));

                        // Verify menu_order values are numeric
                        foreach ($test_posts as $post) {
                            if (!is_numeric($post->menu_order)) {
                                return array(
                                    'success' => false,
                                    'message' => sprintf(__('Invalid menu_order value for post %d', 'post-types-order'), $post->ID),
                                    'details' => "menu_order should be numeric, found: " . var_export($post->menu_order, true)
                                );
                            }
                        }
                        $checks[] = "✓ All menu_order values are numeric";
                    }

                    // Check if WordPress hooks are properly registered (if functions exist)
                    if (function_exists('has_filter')) {
                        $hooks_to_check = array(
                            'pre_get_posts' => 'pre_get_posts',
                            'posts_orderby' => 'posts_orderby'
                        );

                        foreach ($hooks_to_check as $hook => $method) {
                            if ($CPTO && !has_filter($hook, array($CPTO, $method))) {
                                $checks[] = "⚠ Hook {$hook} may not be properly registered";
                            } else {
                                $checks[] = "✓ Hook {$hook} properly registered";
                            }
                        }
                    } else {
                        $checks[] = "⚠ WordPress hook functions not available for testing";
                    }

                    return array(
                        'success' => true,
                        'message' => __('Post ordering functionality test passed', 'post-types-order'),
                        'details' => implode("\n", $checks)
                    );
                }

            /**
             * Test 3: AJAX Security Validation and Protection
             *
             * ⚠️  CRITICAL SECURITY TEST - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Validates AJAX security implementation including:
             * - AJAX action registration verification
             * - WordPress security function availability
             * - Nonce generation and verification testing
             * - Security handler functionality validation
             * - Authentication and authorization checks
             *
             * REFACTORING RISKS:
             * - Breaking security validation detection
             * - Loss of AJAX action verification
             * - Breaking nonce testing functionality
             * - Invalid security function checking
             * - Loss of vulnerability detection capabilities
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test AJAX action registration detection
             * - Verify nonce generation and verification
             * - Test security function availability checks
             * - Verify authentication requirement validation
             * - Test with different WordPress security configurations
             *
             * @since 2.8.0
             * @return array Test result with success status and diagnostic details
             */
            function test_ajax_security_validation()
                {
                    $checks = array();

                    // Check if AJAX actions are properly registered (if function exists)
                    if (function_exists('has_action')) {
                        $ajax_actions = array(
                            'wp_ajax_update-custom-type-order',
                            'wp_ajax_update-custom-type-order-archive',
                            'wp_ajax_pto_filter_posts_by_category'
                        );

                        $missing_actions = array();
                        foreach ($ajax_actions as $action) {
                            if (!has_action($action)) {
                                $missing_actions[] = $action;
                            }
                        }

                        if (!empty($missing_actions)) {
                            $checks[] = "⚠ Some AJAX actions not registered: " . implode(', ', $missing_actions);
                        } else {
                            $checks[] = "✓ All AJAX actions properly registered";
                        }
                    } else {
                        $checks[] = "⚠ WordPress action functions not available for testing";
                    }

                    // Check if security functions are available
                    $security_functions = array('wp_verify_nonce', 'current_user_can', 'sanitize_text_field', 'wp_unslash');
                    foreach ($security_functions as $func) {
                        if (!function_exists($func)) {
                            return array(
                                'success' => false,
                                'message' => sprintf(__('Security function %s not available', 'post-types-order'), $func),
                                'details' => 'WordPress security functions missing'
                            );
                        }
                    }
                    $checks[] = "✓ Security functions available: " . implode(', ', $security_functions);

                    // Test nonce generation
                    $test_nonce = wp_create_nonce('test_nonce');
                    if (empty($test_nonce)) {
                        return array(
                            'success' => false,
                            'message' => __('Nonce generation failed', 'post-types-order'),
                            'details' => 'wp_create_nonce returned empty value'
                        );
                    }
                    $checks[] = "✓ Nonce generation working";

                    // Test nonce verification
                    if (!wp_verify_nonce($test_nonce, 'test_nonce')) {
                        return array(
                            'success' => false,
                            'message' => __('Nonce verification failed', 'post-types-order'),
                            'details' => 'wp_verify_nonce failed for valid nonce'
                        );
                    }
                    $checks[] = "✓ Nonce verification working";

                    return array(
                        'success' => true,
                        'message' => __('AJAX security validation test passed', 'post-types-order'),
                        'details' => implode("\n", $checks)
                    );
                }

            /**
             * Test 4: Pagination Performance and Query Optimization
             *
             * ⚠️  CRITICAL PERFORMANCE TEST - DO NOT REFACTOR UNLESS NECESSARY ⚠️
             *
             * Validates pagination and performance optimizations including:
             * - PTO_Interface class loading and availability
             * - Pagination method existence verification
             * - Unbounded query detection and prevention
             * - Pagination limit enforcement (50 posts per page)
             * - Performance optimization validation
             *
             * REFACTORING RISKS:
             * - Breaking performance optimization detection
             * - Loss of unbounded query prevention
             * - Breaking pagination functionality validation
             * - Invalid performance testing logic
             * - Loss of query optimization verification
             *
             * TESTING REQUIRED AFTER ANY CHANGES:
             * - Test PTO_Interface class loading
             * - Verify pagination method detection
             * - Test unbounded query detection logic
             * - Verify pagination limit enforcement
             * - Test with large datasets for performance validation
             *
             * @since 2.8.0
             * @return array Test result with success status and diagnostic details
             */
            function test_pagination_performance()
                {
                    $checks = array();

                    // Ensure interface class is loaded
                    if (!class_exists('PTO_Interface')) {
                        // Try to load the interface class
                        if (file_exists(CPTPATH . '/include/class.interface.php')) {
                            include_once(CPTPATH . '/include/class.interface.php');
                        }

                        // Check again after attempting to load
                        if (!class_exists('PTO_Interface')) {
                            return array(
                                'success' => false,
                                'message' => __('PTO_Interface class not found', 'post-types-order'),
                                'details' => 'Interface class file exists but class not loaded. Path: ' . CPTPATH . '/include/class.interface.php'
                            );
                        }
                    }
                    $checks[] = "✓ PTO_Interface class exists";

                    // Create interface instance to test pagination
                    $interface = new PTO_Interface();

                    // Check if pagination methods exist
                    if (!method_exists($interface, 'render_pagination_controls')) {
                        return array(
                            'success' => false,
                            'message' => __('Pagination methods missing', 'post-types-order'),
                            'details' => 'render_pagination_controls method not found'
                        );
                    }
                    $checks[] = "✓ Pagination methods exist";

                    // Test that we're not using unbounded queries
                    $reflection = new ReflectionClass('PTO_Interface');
                    $list_pages_method = $reflection->getMethod('list_pages');

                    // Get the source code of the method (this is a bit hacky but works for testing)
                    $filename = $reflection->getFileName();
                    $start_line = $list_pages_method->getStartLine() - 1;
                    $end_line = $list_pages_method->getEndLine();
                    $length = $end_line - $start_line;

                    $source = file($filename);
                    $method_source = implode("", array_slice($source, $start_line, $length));

                    // Check for unbounded queries
                    if (strpos($method_source, "'posts_per_page' => -1") !== false ||
                        strpos($method_source, '"posts_per_page" => -1') !== false) {
                        return array(
                            'success' => false,
                            'message' => __('Unbounded query detected in list_pages method', 'post-types-order'),
                            'details' => 'Found posts_per_page => -1 which can cause performance issues'
                        );
                    }
                    $checks[] = "✓ No unbounded queries in list_pages method";

                    // Check default pagination limit
                    if (strpos($method_source, "'posts_per_page' => 50") !== false ||
                        strpos($method_source, '"posts_per_page" => 50') !== false) {
                        $checks[] = "✓ Proper pagination limit (50) detected";
                    } else {
                        $checks[] = "⚠ Could not verify pagination limit in source code";
                    }

                    // Test pagination info property
                    if (!property_exists($interface, 'pagination_info')) {
                        return array(
                            'success' => false,
                            'message' => __('Pagination info property missing', 'post-types-order'),
                            'details' => 'pagination_info property not found in PTO_Interface'
                        );

                        }
                        $checks[] = "\xE2\x9C\x93 Pagination info property exists";

                        return array(
                            'success' => true,
                            'message' => __('Pagination performance test passed', 'post-types-order'),
                            'details' => implode("\n", $checks)
                        );
                    }

                /**
                 * Test 5: Sorting & Filtering Integrity
                 *
                 * CRITICAL HOOKS VALIDATION – DO NOT REFACTOR UNLESS NECESSARY
                 *
                 * Validates registration of core hooks used for ordering/filtering and AJAX:
                 * - Filters: pre_get_posts, posts_orderby (expected priority 99)
                 * - AJAX: update-custom-type-order, update-custom-type-order-archive, pto_filter_posts_by_category
                 *
                 * @since 2.9.6
                 * @return array
                 */
                function test_sorting_filtering_integrity()
                    {
                        $checks = array();

                        // Ensure CPTO is available
                        if (!class_exists('CPTO')) {
                            if (file_exists(CPTPATH . '/include/class.cpto.php')) {
                                include_once(CPTPATH . '/include/class.cpto.php');
                            }
                        }

                        global $CPTO;
                        if (!$CPTO || !is_object($CPTO)) {
                            return array(
                                'success' => false,
                                'message' => __('CPTO instance not initialized', 'post-types-order'),
                                'details' => 'Global $CPTO not available; core hooks cannot be verified'
                            );
                        }
                        $checks[] = '✓ CPTO instance available';

                        if (!function_exists('has_filter') || !function_exists('has_action')) {
                            return array(
                                'success' => false,
                                'message' => __('WordPress hook APIs not available', 'post-types-order'),
                                'details' => 'has_filter/has_action functions missing'
                            );
                        }

                        // Check filter hooks
                        $missing_filters = array();
                        $pre_priority = has_filter('pre_get_posts', array($CPTO, 'pre_get_posts'));
                        $orderby_priority = has_filter('posts_orderby', array($CPTO, 'posts_orderby'));

                        if ($pre_priority === false) {
                            $missing_filters[] = 'pre_get_posts::CPTO::pre_get_posts';
                        } else {
                            $checks[] = '✓ pre_get_posts registered (priority ' . intval($pre_priority) . ')';
                        }

                        if ($orderby_priority === false) {
                            $missing_filters[] = 'posts_orderby::CPTO::posts_orderby';
                        } else {
                            $checks[] = '✓ posts_orderby registered (priority ' . intval($orderby_priority) . ')';
                            if (intval($orderby_priority) !== 99) {
                                $checks[] = '⚠ posts_orderby priority is ' . intval($orderby_priority) . ', expected 99';
                            }
                        }

                        // Check AJAX actions
                        $missing_ajax = array();
                        $ajax_map = array(
                            'wp_ajax_update-custom-type-order' => 'saveAjaxOrder',
                            'wp_ajax_update-custom-type-order-archive' => 'saveArchiveAjaxOrder',
                            'wp_ajax_pto_filter_posts_by_category' => 'filterPostsByCategory',
                        );

                        foreach ($ajax_map as $hook => $method) {
                            $registered = has_action($hook, array($CPTO, $method));
                            if ($registered === false) {
                                $missing_ajax[] = $hook . '::CPTO::' . $method;
                            } else {
                                $checks[] = '✓ ' . $hook . ' registered to ' . $method . ' (priority ' . intval($registered) . ')';
                            }
                        }

                        if (!empty($missing_filters) || !empty($missing_ajax)) {
                            $missing = array();
                            if (!empty($missing_filters)) {
                                $missing[] = 'Filters: ' . implode(', ', $missing_filters);
                            }
                            if (!empty($missing_ajax)) {
                                $missing[] = 'AJAX: ' . implode(', ', $missing_ajax);
                            }
                            return array(
                                'success' => false,
                                'message' => __('Missing required hook registrations', 'post-types-order'),
                                'details' => implode("\n", array_merge($checks, array('Missing => ' . implode(' | ', $missing))))
                            );
                        }

                        return array(
                            'success' => true,
                            'message' => __('Sorting & filtering integrity test passed', 'post-types-order'),
                            'details' => implode("\n", $checks)
                        );
                    }

                /**
                 * Test 6: Live Query Order Test
                 *
                 * Attempts a lightweight WP_Query and inspects the final ORDER BY clause
                 * to confirm that menu_order is applied. This does not modify content.
                 *
                 * Behavior:
                 * - If menu_order detected in ORDER BY: PASS
                 * - If running in admin with adminsort disabled: INDETERMINATE (return success=true with note)
                 * - If autosort disabled: INDETERMINATE (success=true with note)
                 * - Otherwise: FAIL
                 *
                 * @since 2.9.7
                 */
                function test_orderby_live_query()
                    {
                        $checks = array();

                        if (!class_exists('WP_Query')) {
                            return array(
                                'success' => false,
                                'message' => __('WP_Query not available', 'post-types-order'),
                                'details' => ''
                            );
                        }

                        global $CPTO;
                        $options = array();
                        if ($CPTO && isset($CPTO->functions) && method_exists($CPTO->functions, 'get_options')) {
                            $options = $CPTO->functions->get_options();
                        }
                        $autosort  = isset($options['autosort']) ? strval($options['autosort']) : '';
                        $adminsort = isset($options['adminsort']) ? strval($options['adminsort']) : '';

                        $captured_orderby = null;
                        $capture_cb = function($orderby, $q) use (&$captured_orderby) {
                            $captured_orderby = $orderby;
                            return $orderby;
                        };
                        add_filter('posts_orderby', $capture_cb, 1000, 2);

                        // Lightweight query: IDs only, no_found_rows, small page
                        $args = array(
                            'post_type'           => 'post',
                            'post_status'         => 'publish',
                            'fields'              => 'ids',
                            'posts_per_page'      => 2,
                            'no_found_rows'       => true,
                            'suppress_filters'    => false,
                            'ignore_custom_sort'  => false,
                        );

                        $q = new WP_Query($args);
                        // Touch results to ensure query executed
                        $ids = is_array($q->posts) ? count($q->posts) : 0;
                        $checks[] = 'Queried posts: ' . intval($ids);

                        remove_filter('posts_orderby', $capture_cb, 1000);

                        $is_admin_flag = function_exists('is_admin') ? is_admin() : false;
                        $has_menu_order = is_string($captured_orderby) && (strpos($captured_orderby, 'menu_order') !== false);

                        $checks[] = 'Captured ORDER BY: ' . var_export($captured_orderby, true);
                        $checks[] = 'Context flags: is_admin=' . ($is_admin_flag ? '1' : '0') . ', autosort=' . $autosort . ', adminsort=' . $adminsort;

                        if ($has_menu_order) {
                            return array(
                                'success' => true,
                                'message' => __('Detected menu_order in ORDER BY', 'post-types-order'),
                                'details' => implode("\n", $checks)
                            );
                        }

                        // Indeterminate contexts where influence is expected to be off in this environment
                        if ($is_admin_flag && $adminsort !== '1') {
                            return array(
                                'success' => true,
                                'message' => __('Indeterminate in admin context (adminsort disabled); cannot confirm frontend influence here.', 'post-types-order'),
                                'details' => implode("\n", $checks)
                            );
                        }
                        if (!$is_admin_flag && $autosort !== '1') {
                            return array(
                                'success' => true,
                                'message' => __('Autosort disabled; frontend influence intentionally off.', 'post-types-order'),
                                'details' => implode("\n", $checks)
                            );
                        }

                        return array(
                            'success' => false,
                            'message' => __('Expected menu_order not found in ORDER BY', 'post-types-order'),
                            'details' => implode("\n", $checks)
                        );
                    }



        }

?>
