import { __ } from "@wordpress/i18n";

import ServerSideRender from "@wordpress/server-side-render";

import { useSelect } from "@wordpress/data";

import Select from "react-select";

import { useBlockProps, InspectorControls } from "@wordpress/block-editor";

import { PanelBody, PanelRow } from "@wordpress/components";

import metadata from "./block.json";
import "./editor.scss";

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({
    className: "single-post-query",
  });

  const selectedPost = attributes.selectedPost;
  const { posts } = useSelect((select) => {
    const { getEntityRecords } = select("core");
    return {
      posts: getEntityRecords("postType", "post", {
        status: "publish",
      }),
    };
  });
  let options = [];
  if (posts) {
    options.push({ value: 0, label: "Select a post" });
    posts.forEach((post) => {
      options.push({ value: post.id, label: post.title.rendered });
    });
  } else {
    options.push({ value: 0, label: "Loading..." });
  }

  const handleSelectChange = (selectedPost) => {
    setAttributes({ selectedPost: JSON.stringify(selectedPost) });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Select A Post", "wpstarterplugin")} initialOpen={true}>
          <PanelRow>
            <Select
              className="wpstarterplugin-block-select"
              name="select-two"
              value={JSON.parse(selectedPost)}
              onChange={handleSelectChange}
              noOptionsMessage={() => "No Posts Found"}
              options={options}
              isClearable={true}
              isMulti={false}
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
}
