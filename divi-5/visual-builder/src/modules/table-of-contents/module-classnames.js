const { textOptionsClassnames } = window?.divi?.module;

export const moduleClassnames = ( {
        classnamesInstance,
        attrs,
} ) => {
        classnamesInstance.add( textOptionsClassnames( attrs?.module?.advanced?.text, { orientation: false } ) );
};
