/**
 * Blocks.
 */
import helloWorld from './hello-world/block';
const blocks = [ helloWorld ];
const { registerBlockType } = window.wp.blocks;

blocks.forEach( ( settings ) => registerBlockType( settings.name, settings ) );
