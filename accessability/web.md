#use semantic elements
    1. actions
        1.1. a for a link
        1.2. button for an onpage action
        1.3. hierarchical headings

    2. landmarks
        2.1 header for the header
        2.2 nav for a navigation bar
        2.3 main for the main content
        2.4 aside for content that is related to the main
        2.5 section for content that is seperate from the main and has no tag of its own add aria label to section
        2.6 footer for the footer


#Use focus states
    1. basicly set a focus style with :focus
    2. set tabindex to 0

#use aria 
    1. use aria to communicate what your component/element is doing

#meaningfull images
    1. use alt="" for decorative images
    2. allways set alt on images
    3. try css background-image for decorative images
    4. on small icons set role="img" aria-hidden="true"
    5. use aria-hidden="true" for inline svg images
    6. use clear description in alt for non decorative images

#language
    1. use correct lang attribute in the html tag for your language
    2. use the correct language in element where you use a diffrent language than set the html tag


#zoom
    1. avoid horizontal scrolling
        1.1 make site responsive
        1.2 use svg over png
        1.3 make sure elements are also responsive
    2. all content and functionality is available on zoom
        make sure there are no hidden tabs on a zoomed page
    3. avoid text in images
    4. provide space for key content
        4.1 use svg over png/jpg
        4.2 show mobile adds only for mobile devices
        4.3 set view port < meta name="viewport" content="width=device-width, initial-scale=1">
    5. make text resizeable
        5.1 use rem over px where text and text containers are involved

