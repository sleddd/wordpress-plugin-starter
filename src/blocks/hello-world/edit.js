import { __ } from "@wordpress/i18n";

import ServerSideRender from "@wordpress/server-side-render";

import { useBlockProps, InspectorControls } from "@wordpress/block-editor";

import { TextControl, PanelBody, PanelRow } from "@wordpress/components";

import metadata from "./block.json";
import "./editor.scss";

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({
    className: "hello-world",
  });

  const { textField } = attributes;

  const onChangeTextField = (newText) => {
    setAttributes({ textField: newText });
  };

  return (
    <>
      <InspectorControls {...blockProps}>
        <PanelBody
          title={__("Hello World Settings", "wpstarterplugin")}
          initialOpen={true}
        >
          <PanelRow>
            <TextControl
              label={__("TextField Text", "wpstarterplugin")}
              value={textField}
              onChange={onChangeTextField}
              help={__("Text display in example block", "wpstarterplugin")}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <ServerSideRender
          block={metadata.name}
          skipBlockSupportAttributes
          attributes={attributes}
        />
      </div>
    </>
  );
};