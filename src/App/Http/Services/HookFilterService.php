<?php

namespace Dinesh\Magento\App\Http\Services;

class HookFilterService
{
    // Stores the filters by name with priority and arguments
    protected static $filters = [];

    // Stores the hooks by name with priority and arguments
    protected static $hooks = [];

    // Private constructor to prevent instantiation
    private function __construct() {}

    /**
     * Add a filter callback to a specific filter name with a priority and number of arguments.
     *
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $argsCount
     * @return void
     */
    public static function addFilter($name, callable $callback, $priority = 10, $argsCount = 1)
    {
        if (!isset(self::$filters[$name])) {
            self::$filters[$name] = [];
        }

        // Store the filter with priority and args count
        self::$filters[$name][] = ['callback' => $callback, 'priority' => $priority, 'argsCount' => $argsCount];

        // Sort the filters by priority (ascending)
        usort(self::$filters[$name], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }

    /**
     * Add a hook callback to a specific hook name with a priority and number of arguments.
     *
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $argsCount
     * @return void
     */
    public static function addHook($name, callable $callback, $priority = 10, $argsCount = 1)
    {
        if (!isset(self::$hooks[$name])) {
            self::$hooks[$name] = [];
        }

        // Store the hook with priority and args count
        self::$hooks[$name][] = ['callback' => $callback, 'priority' => $priority, 'argsCount' => $argsCount];

        // Sort the hooks by priority (ascending)
        usort(self::$hooks[$name], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }

    /**
     * Apply all filters registered for the given filter name to the values.
     * Supports multiple arguments like WordPress.
     *
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function applyFilters($name, ...$args)
    {
        // Check if there are filters for the given name
        if (isset(self::$filters[$name])) {
            foreach (self::$filters[$name] as $filter) {
                $callback = $filter['callback'];
                // Call the filter with multiple arguments (respecting args count)
                call_user_func_array($callback, array_slice($args, 0, $filter['argsCount']));
            }
        }
    }

    /**
     * Trigger all hooks registered for the given hook name with priority.
     * Supports multiple arguments like WordPress.
     *
     * @param string $name
     * @param mixed ...$args
     * @return void
     */
    public static function applyHooks($name, ...$args)
    {
        // Check if there are hooks for the given name
        if (isset(self::$hooks[$name])) {
            foreach (self::$hooks[$name] as $hook) {
                $callback = $hook['callback'];
                // Call the hook with multiple arguments (respecting args count)
                call_user_func_array($callback, array_slice($args, 0, $hook['argsCount']));
            }
        }
    }
}
