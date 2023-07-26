import { __ } from "@wordpress/i18n";

import ServerSideRender from "@wordpress/server-side-render";

import { useSelect } from "@wordpress/data";

import apiFetch from "@wordpress/api-fetch";

import AsyncSelect from "react-select/async";

import { useBlockProps, InspectorControls } from "@wordpress/block-editor";

import { PanelBody, PanelRow } from "@wordpress/components";

import metadata from "./block.json";
import "./editor.scss";

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({
    className: "single-post-query",
  });

  const selectedPost = attributes.selectedPost;

  // Function to get posts from REST API.
  const getPosts = async (searchString) => {
    const posts = await apiFetch({
      path: `/wp/v2/search?type=post&per_page=5&search=${searchString}`,
    });
    let options = [];
    if (posts) {
      posts.forEach((post) => {
        options.push({ value: post.id, label: post.title });
      });
    }
    return options;
  };

  // Sets up options for React-Select with Async.
  const promiseOptions = (inputValue) =>
    new Promise((resolve) => {
      console.log(inputValue);
      setTimeout(() => {
        resolve(getPosts(inputValue));
      }, 1000);
    });


  // Handles updating attribute when post is selected.
  const handleSelectChange = (selectedPost) => {
    setAttributes({ selectedPost: JSON.stringify(selectedPost) });
  };

  return (
    <>
      <InspectorControls>
        <PanelBody
          title={__("Select A Post", "wpstarterplugin")}
          initialOpen={true}
        >
          <PanelRow>
            <AsyncSelect
              className="wpstarterplugin-block-select"
              name="wpstarterplugin-select-two"
              placeholder={"Select a post..."}
              value={JSON.parse(selectedPost)}
              onChange={handleSelectChange}
              noOptionsMessage={() => "No Posts Found"}
              cacheOptions
              defaultOptions={true}
              loadOptions={promiseOptions}
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
