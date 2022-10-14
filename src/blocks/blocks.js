/**
 * Blocks.
 */
import helloWorld from './hello-world/block';
import exampleOne from './example-1/block';
const blocks = [ helloWorld, exampleOne ];
const { registerBlockType } = window.wp.blocks;

blocks.forEach( ( settings ) => registerBlockType( settings.name, settings ) );
