// Block data
import metadata from './block.json';

/**
 * Components.
 */
import { Edit } from './js/edit';
import { Save } from './js/save';

export default {
	...metadata,
	edit: Edit,
	save: Save,
};
