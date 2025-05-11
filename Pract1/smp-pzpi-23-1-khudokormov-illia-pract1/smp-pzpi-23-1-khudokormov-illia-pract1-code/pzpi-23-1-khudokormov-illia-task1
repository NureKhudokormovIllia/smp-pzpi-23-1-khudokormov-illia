#!/bin/bash

display_sequence() {
    local repeat_count=$1
    local symbol=$2
    local idx
    for ((idx = 0; idx < repeat_count; idx++)); do
        echo -n "$symbol"
    done
}

generate_layer() {
    local layer_size=3
    local tier_height=$1
    local symbol_type=$2
    while ((tier_height > 0)); do
        display_sequence $tier_height ' '
        ((tier_height--))
        display_sequence $layer_size $symbol_type
        echo ""
        ((layer_size += 2))
        if [[ "$symbol_type" == "#" ]]; then
            symbol_type="*"
        else
            symbol_type="#"
        fi
    done
}

segment_count=2
base_section=2
full_tree_height=$1

if [[ $full_tree_height -lt 8 ]]; then
    echo "Error: Minimum tree height is 8" >&2
    exit 1
fi

auxiliary_part=4
snow_density=$((full_tree_height % 2 == 0 ? 3 : 2))
total_snow_width=$((full_tree_height - auxiliary_part + snow_density))

if [[ $total_snow_width -ne $2 && $total_snow_width -ne $(($2 - 1)) ]]; then
    echo "Snow width for this tree size must be $total_snow_width" >&2
    exit 1
fi

calculated_tier=$(((full_tree_height - auxiliary_part) / 2))

set -f
display_sequence $((calculated_tier + 1)) " "
echo "*"

symbol='#'
until ((segment_count == 0)); do
    if [[ $segment_count -eq 1 && $((calculated_tier % 2)) -eq 1 ]]; then
        symbol='*'
    fi
    generate_layer $calculated_tier $symbol
    ((segment_count--))
done

for idx in 1 2; do
    display_sequence $calculated_tier " "
    echo "###"
done

display_sequence $total_snow_width '*'
echo ""
set +f