export const log = (...props) => {
    const isEnabled = Statamic.$config.get("add-to-basket:enable_debug_log");

    isEnabled && console.log(...props)
};
