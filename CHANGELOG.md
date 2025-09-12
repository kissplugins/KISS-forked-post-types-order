# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.9.8] - 2025-09-12

### Added
- Tiny inline helper note on the Self Tests page under the "Live Query Order Test" explaining why it may show "Indeterminate" in admin and how to get a definite PASS (run on frontend with Autosort enabled or temporarily enable Admin Sort)

## [2.9.7] - 2025-09-12

### Added
- New Self Test: "Live Query Order Test" to run a lightweight WP_Query and confirm menu_order is present in ORDER BY when applicable

### Notes
- Test reports indeterminate (passes with a note) when running in admin with adminsort disabled or when autosort is disabled


## [2.9.6] - 2025-09-12

### Added
- New Self Test: "Sorting & Filtering Integrity" to validate registration of core hooks (pre_get_posts, posts_orderby) and related AJAX actions

### Notes
- Helps catch regressions if hook bindings are changed or removed


## [2.9.5] - 2025-09-12

### Added
- Self Tests quick-access link in the Plugins page action links (points to Tools → KISS Re-Order Self Tests)

### Notes
- No functional changes to tests; navigation convenience only


## [2.9.4] - 2025-09-11

### Added
- Protective documentation and inline safeguards around ACF inline output to prevent accidental refactors/removals
- Clear retrieval-order notes and required post-change test checklist in add_acf_fields_to_interface()

### Notes
- No functional changes; comments only for developer safety
- UI remains the same (one-line ACF values)


## [2.9.3] - 2025-09-11

### Changed
- ACF sub-headline values now display in a single inline row on each draggable post item:
  `post_sub_headline: … | post_short_sub_headline: … | post_longer_sub_headline: …`

### Notes
- Keeps field names as labels as requested
- Uses existing `pto/acf_fields` filter for customization
- Non-intrusive UI; reuses current styles


## [2.9.2] - 2025-09-11

### Added
- Output ACF field names and values directly on draggable post rows:
  - post_sub_headline
  - post_short_sub_headline
  - post_longer_sub_headline

### Improved
- Robust retrieval for Group fields: if sub-headlines live under a `post_settings` group, values are pulled from the group automatically with safe meta fallbacks
- Cleaned up previous debug visuals; interface now shows concise field badges only

### Developer Notes
- Displayed labels default to the ACF field names for clarity; can be customized via `pto/acf_fields` filter
- No changes to existing labels elsewhere; minimal, focused update

## [2.9.1] - 2025-01-11

### Added
- **ACF Fields Integration**: Added support for displaying Advanced Custom Fields in the post ordering interface
- **Sub-Headline Display**: Automatic detection and display of ACF sub-headline fields including:
  - Sub Headline
  - Sub Headline (Shorter)
  - Sub Headline (Longer)
- **Smart Field Truncation**: Long ACF field values are automatically truncated with ellipsis for clean display
- **Hover Tooltips**: Full ACF field values shown on hover for truncated content

### Enhanced
- **Interface Layout**: Improved post item layout to better accommodate ACF field display
- **Visual Hierarchy**: Clear separation between post title and ACF field information
- **Responsive Design**: ACF fields display cleanly without disrupting drag-and-drop functionality

### Technical Implementation
- **Filter Integration**: Uses existing `pto/interface_item_data` filter for seamless integration
- **Conditional Loading**: ACF fields only display when ACF plugin is active
- **Customizable Fields**: Developers can modify displayed fields using `pto/acf_fields` filter
- **Performance Optimized**: Efficient field retrieval without impacting interface performance
- **Status**: Successfully implemented and tested - function hooks properly into interface walker

### Styling
- **Professional Appearance**: Clean, subtle styling for ACF field display
- **Consistent Design**: Matches existing plugin design language
- **Accessibility**: Proper contrast and readable typography for ACF content

## [2.9.0] - 2025-01-11

### Added
- **Coming Soon Post Status Support**: Added full integration for "KISS Coming Soon" post status in the draggable interface
- **Visual Status Indicators**: Coming Soon posts now display with distinctive orange badges in the post ordering interface
- **Plugin Integration**: Automatic detection and integration with KISS Coming Soon Post Status plugin
- **Flexible Status Handling**: Support for custom Coming Soon implementations beyond the specific plugin

### Enhanced
- **Post Status Queries**: Updated all database queries to include 'coming_soon' status alongside existing statuses
- **Interface Display**: Enhanced walker class to detect and visually distinguish Coming Soon posts
- **Helper Methods**: Added centralized methods for Coming Soon status detection and label retrieval
- **CSS Styling**: Added professional styling for Coming Soon status badges with orange color scheme

### Technical Implementation
- **Dynamic Status Detection**: Automatically detects if Coming Soon status is available via plugin or custom implementation
- **Fallback Handling**: Graceful fallback when Coming Soon plugin is not active
- **Consistent Integration**: Uses plugin's custom labels when available, falls back to default "Coming Soon" text
- **Performance Optimized**: Efficient status checking without impacting existing functionality

### Compatibility
- **CSPS Plugin**: Full integration with CSPS Coming Soon Post Status plugin constants and methods
- **Custom Implementations**: Support for any custom Coming Soon status implementations
- **Backward Compatible**: No breaking changes to existing functionality

## [2.8.9] - 2025-01-04

### Fixed
- **CRITICAL PERFORMANCE FIX**: Eliminated unbounded query in filtered post reordering
- **SQL Security**: Replaced string interpolation with proper prepared statements
- **Memory Optimization**: Reduced memory usage by eliminating full post dataset loading
- **Scalability**: Fixed timeout issues on sites with 1000+ posts

### Performance Improvements
- **Gap-Based Reordering**: Implemented efficient algorithm that doesn't require loading all posts
- **Targeted Queries**: Only loads posts being reordered, not entire dataset
- **Prepared Statements**: Enhanced security with proper SQL parameter binding
- **Memory Efficiency**: Eliminated potential memory exhaustion on large sites

### Technical Implementation
- **Algorithm Optimization**: Replaced O(n) full dataset approach with O(k) targeted approach
- **SQL Injection Prevention**: Used proper placeholder-based prepared statements
- **Error Handling**: Added validation for empty datasets and edge cases
- **Code Documentation**: Comprehensive comments explaining the optimization approach

### Impact
- **Large Sites**: Sites with 1000+ posts no longer experience timeouts during reordering
- **Memory Usage**: Significant reduction in memory consumption during filtered operations
- **Security**: Eliminated SQL injection vulnerability in filtered reordering
- **Maintainability**: Cleaner, more efficient code with better documentation

## [2.8.8] - 2025-01-04

### Added
- **Plugin Version Display**: Added plugin version number to Debug Information section
- **Version Tracking**: Easy identification of current plugin version during troubleshooting
- **Debug Enhancement**: Improved diagnostic information with version context

### User Experience Improvements
- **Quick Version Check**: Instantly see plugin version without navigating to plugins page
- **Troubleshooting Aid**: Version information helps with support and debugging
- **Professional Display**: Clean formatting consistent with other debug information

### Technical Implementation
- **Dynamic Version**: Uses PTO_VERSION constant for accurate version display
- **Fallback Handling**: Shows "Unknown" if version constant is not defined
- **Secure Output**: Properly escaped using esc_html() for security
- **Consistent Styling**: Matches existing debug information formatting

## [2.8.7] - 2025-01-04

### Added
- **Self Tests Button**: Quick access button in top right corner of main KISS Re-Order page
- **Professional Integration**: Seamlessly integrated with WordPress admin design standards
- **Responsive Design**: Adapts to different screen sizes and mobile devices
- **Visual Enhancement**: Dashicons tools icon with hover effects

### User Experience Improvements
- **One-Click Access**: Direct access to self-tests from main ordering interface
- **Workflow Enhancement**: No need to navigate through WordPress menus
- **Visual Clarity**: Clear button placement and professional styling
- **Mobile Friendly**: Responsive design works on all device sizes

### Technical Implementation
- **Header Integration**: Added to main page header with proper positioning
- **CSS Styling**: Professional button styling with hover states
- **Responsive CSS**: Media queries for mobile device compatibility
- **WordPress Standards**: Uses admin_url() and proper WordPress functions

### Design Features
- **Top Right Placement**: Intuitive location for secondary actions
- **Dashicons Integration**: Uses WordPress admin tools icon
- **Hover Effects**: Professional interaction feedback
- **Consistent Styling**: Matches WordPress admin button standards

## [2.8.6] - 2025-01-04

### Added
- **Quick Edit Feature**: Right arrow (→) links next to post titles for instant editor access
- **New Tab Opening**: Quick edit links open post editor in new browser tab
- **Professional Styling**: Hover effects and focus states for accessibility
- **Drag-and-Drop Protection**: Quick edit links don't interfere with reordering functionality

### User Experience Improvements
- **One-Click Editing**: Click the → arrow to instantly open post editor
- **Workflow Enhancement**: Edit posts without losing your place in the ordering interface
- **Visual Feedback**: Hover effects show interactive elements clearly
- **Accessibility**: Proper focus states and ARIA labels for screen readers

### Technical Implementation
- **Walker Integration**: Added to post title display in class.walkers.php
- **CSS Styling**: Professional hover and focus effects in cpt.css
- **JavaScript Protection**: Prevents interference with drag-and-drop functionality
- **Security**: Uses WordPress get_edit_post_link() for proper URL generation

### Design Features
- **Subtle Appearance**: Gray arrow that highlights blue on hover
- **Consistent Spacing**: Proper margin and padding for clean layout
- **Responsive Design**: Works across different screen sizes
- **WordPress Standards**: Follows WordPress admin color scheme

## [2.8.5] - 2025-01-04

### Security
- **CRITICAL SECURITY FIX**: Completely secured debug-category-filter.php
- **Eliminated wp-config.php exposure**: Removed direct WordPress configuration access
- **Implemented WordPress authentication**: Requires admin login and capabilities
- **Added nonce verification**: Enhanced CSRF protection for debug access
- **Removed direct file access**: No more bypassing WordPress security system

### Debug Tool Improvements
- **Secure Integration**: Debug tool now integrates with self-tests system
- **Professional UI**: WordPress admin styling and proper form tables
- **Enhanced Functionality**: Better post type and taxonomy analysis
- **Access Control**: Administrator-only access with proper capability checks
- **User Experience**: Clean, professional interface with status indicators

### Security Features
- **WordPress Authentication Required**: Must be logged in as administrator
- **Capability Verification**: Requires 'manage_options' capability
- **Nonce Protection**: CSRF protection for all debug requests
- **No Direct Access**: Prevents any direct file execution
- **Integrated Access**: Available through Tools → KISS Re-Order Self Tests

### Technical Improvements
- **Secure File Structure**: Proper WordPress integration patterns
- **Error Handling**: Graceful handling of missing functions or data
- **Internationalization**: Proper translation support throughout
- **Code Standards**: Follows WordPress coding and security standards

## [2.8.4] - 2025-01-04

### Added
- **Self-Tests Documentation**: Comprehensive PHPDoc comments for all self-test methods
- **Self-Tests Protection**: Critical function safeguards against unnecessary refactoring
- **Developer Safety**: Detailed risk assessments for self-testing system components
- **Testing Requirements**: Specific validation procedures for self-test modifications

### Self-Tests Protection Features
- **Class-Level Protection**: Comprehensive warning for entire PTO_SelfTests class
- **Method-Level Safeguards**: Individual protection for all 8 critical methods
- **Risk Documentation**: Detailed refactoring risks for each component
- **Testing Procedures**: Required validation steps after any changes

### Protected Self-Test Components
- `PTO_SelfTests` class - Core diagnostic system
- `__construct()` - WordPress hook initialization
- `add_tools_menu()` - Admin menu integration
- `render_tests_page()` - UI interface rendering
- `run_single_test()` - AJAX test execution handler
- `execute_test()` - Test coordination and routing
- `test_database_connectivity()` - Database validation
- `test_post_ordering_functionality()` - Core functionality testing
- `test_ajax_security_validation()` - Security verification
- `test_pagination_performance()` - Performance optimization validation

### Documentation Improvements
- **PHPDoc Standards**: Complete @since, @param, @return documentation
- **Risk Assessment**: Detailed explanation of refactoring consequences
- **Testing Integration**: References to validation procedures
- **Dependency Mapping**: Clear component relationship documentation

## [2.8.3] - 2025-01-04

### Added
- **Code Protection**: Added comprehensive warning comments to critical core functions
- Detailed refactoring risk assessments for all critical methods
- Testing requirements documentation for each protected function
- Dependency mapping for core functionality relationships

### Code Protection Features
- **Critical Function Identification**: Marked 6 core functions with protection warnings
- **Risk Assessment**: Detailed documentation of refactoring risks for each function
- **Testing Requirements**: Specific test procedures required after any changes
- **Dependency Documentation**: Clear mapping of function relationships and dependencies

### Protected Functions
- `saveAjaxOrder()` - Core AJAX post ordering with security validation
- `processOrderData()` - Database update processing with permission checks
- `filterPostsByCategory()` - Category filtering functionality
- `get_term_counts_optimized()` - Performance-critical N+1 query fix
- `list_pages()` - Pagination and post listing interface
- `pre_get_posts()` and `posts_orderby()` - WordPress core integration hooks

### Developer Safety
- Clear warnings against unnecessary refactoring
- Comprehensive risk documentation
- Required testing procedures for any changes
- Self-test integration for validation

## [2.8.2] - 2025-01-04

### Added
- **Self-Tests Enhancement**: Dynamic test summary at top of Self Tests page
- Real-time summary showing "X of Y Tests Completed" with pass/fail status
- Color-coded summary: Green for all passed, Red for failures, Blue for in progress
- Summary automatically updates as tests complete
- Summary hides when no tests have been run

### User Experience Improvements
- Immediate visual feedback on overall test status
- Clear indication of testing progress
- Professional summary display with color coding
- Summary integrates seamlessly with existing test interface

### Technical Implementation
- JavaScript-powered dynamic summary updates
- CSS styling for different summary states (pass/fail/partial)
- Automatic summary refresh after each test completion
- Summary state management with show/hide functionality

## [2.8.1] - 2025-01-04

### Fixed
- **Self-Tests**: Improved class loading and WordPress function availability checks
- Enhanced PTO_Interface class loading in pagination performance test
- Made AJAX action and hook registration tests more resilient
- Added fallback handling for WordPress functions that may not be available during testing
- Improved error handling and diagnostic messages in self-tests

### Technical Improvements
- Better class existence checking with automatic include attempts
- More robust method existence validation for core functionality
- Enhanced WordPress environment detection in test framework
- Improved error messages with detailed diagnostic information

## [2.8.0] - 2025-01-04

### Added
- **NEW FEATURE**: KISS Re-Order Self Tests page under WordPress Tools menu
- Comprehensive self-testing system to detect regressions and bugs after refactoring
- Four critical core tests covering database, ordering, security, and performance
- Real-time test execution with AJAX-powered interface
- Dynamic version display in page title
- Detailed test results with execution timing and diagnostic information

### Self-Test Features
- **Database Connectivity Test**: Verifies database access and menu_order column functionality
- **Post Ordering Functionality Test**: Validates core ordering mechanisms and WordPress hooks
- **AJAX Security Validation Test**: Ensures security handlers and nonce validation work correctly
- **Pagination Performance Test**: Confirms no unbounded queries and proper pagination limits

### Developer Experience
- Individual test execution with real-time status updates
- "Run All Tests" functionality for comprehensive validation
- Clear pass/fail indicators with detailed diagnostic output
- Execution time tracking for performance monitoring
- Professional UI with color-coded status indicators

### Technical Implementation
- New `PTO_SelfTests` class with comprehensive test framework
- AJAX-powered test execution with proper security validation
- Reflection-based code analysis for performance validation
- Exception handling with detailed error reporting
- WordPress admin integration under Tools menu

## [2.7.0] - 2025-01-04

### Security
- **CRITICAL SECURITY**: Implemented comprehensive AJAX input validation and sanitization
- Added authentication checks for all AJAX handlers
- Enhanced nonce validation with proper error handling
- Added capability checks for post type access
- Implemented post ownership validation before allowing edits
- Added input format validation with regex patterns
- Enhanced error messages with proper internationalization

### Security Improvements
- **Authentication**: All AJAX handlers now verify user login status
- **Authorization**: Capability checks for `edit_posts` and post-type-specific permissions
- **Input Validation**: Comprehensive sanitization of all user inputs
- **Nonce Security**: Strengthened nonce validation with detailed error responses
- **Data Validation**: Post ID validation and ownership verification
- **Format Validation**: Regex validation for taxonomy filters and post type names
- **Error Handling**: Secure error responses using `wp_send_json_error()`

### Technical Security Details
- Enhanced `saveAjaxOrder()` with multi-layer validation
- Improved `filterPostsByCategory()` with taxonomy/term existence checks
- Strengthened `saveArchiveAjaxOrder()` with data format validation
- Added `processOrderData()` method with post ownership verification
- Enhanced `admin_init()` with post type access control

### Vulnerability Fixes
- Fixed potential privilege escalation in post reordering
- Prevented unauthorized access to post type interfaces
- Blocked invalid taxonomy/term manipulation
- Secured against malformed input data attacks
- Protected against CSRF attacks with enhanced nonce validation

## [2.6.0] - 2025-01-04

### Added
- **CRITICAL PERFORMANCE**: Implemented pagination for main post interface
- Added comprehensive pagination controls with page navigation, jump-to-page functionality
- Pagination now limits posts to 50 per page instead of loading all posts
- Added pagination support to AJAX category filtering

### Fixed
- **CRITICAL PERFORMANCE**: Eliminated unbounded queries in main interface (`posts_per_page => -1`)
- Fixed AJAX filter queries to use pagination instead of loading all posts
- Improved memory usage by limiting post loading to manageable chunks

### Performance Improvements
- Main interface now loads 50 posts per page instead of all posts (unlimited)
- Significantly reduced memory usage on sites with thousands of posts
- Faster page load times and improved responsiveness
- AJAX filtering now respects pagination limits

### User Experience
- Added intuitive pagination controls with previous/next navigation
- Implemented page number links with smart ellipsis for large page counts
- Added "jump to page" functionality for quick navigation
- Pagination state preserved during category filtering

### Technical Details
- Added `render_pagination_controls()` method for consistent pagination UI
- Enhanced AJAX responses to include pagination metadata
- Implemented proper URL parameter handling for pagination state
- Added pagination info storage in interface class

## [2.5.0] - 2025-01-04

### Fixed
- **CRITICAL PERFORMANCE**: Fixed N+1 query problem in category count calculation
- Replaced individual WP_Query calls for each taxonomy term with single optimized SQL query
- Added intelligent caching for term counts to prevent repeated expensive calculations
- Reduced database queries from N+1 to 1 for category dropdown generation

### Performance Improvements
- Category filter now loads significantly faster on sites with many taxonomy terms
- Memory usage reduced by eliminating multiple WP_Query instances
- Added 5-minute caching for term count calculations

### Technical Details
- Added `get_term_counts_optimized()` method for efficient batch term counting
- Implemented WordPress object caching with proper cache invalidation
- Optimized SQL query uses proper JOINs and GROUP BY for maximum efficiency

## [2.4.1] - 2025-01-04

### Changed
- Updated menu labels and page titles from "Re-Order" to "KISS Re-Order" for better branding consistency

## [2.4.0] - 2025-01-04

### Removed
- Plugin Information & Support info box from the Post Types Order interface for cleaner UI

### Fixed
- Dropdown category count accuracy - counts now reflect posts specific to the current post type instead of all post types
- Category filter debugging information now shows accurate post counts per taxonomy term

### Technical Details
- Replaced `$term->count` with custom WP_Query to calculate accurate post counts per taxonomy term
- Updated both dropdown generation and debug display logic to use post-type-specific queries
- Improved code maintainability by using consistent counting methodology

## Previous Versions

For changelog entries prior to version 2.4.0, please refer to the `readme-original.txt` file which contains the complete version history from the original plugin.
