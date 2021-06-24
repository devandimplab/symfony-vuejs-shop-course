import { setCookie, getCookie } from "../../../utils/cookie-manager";

window.toggleFiltersVisibility = function toggleFiltersVisibility(section) {
    const filtersKey = "filtersVisible_" + section;
    const filtersSavedValue = getCookie(filtersKey);

    const visibleValue = filtersSavedValue === 'false';

    setCookie(filtersKey, visibleValue, { secure: true, "max-age": 3600 });
}

window.changeFiltersBlockVisibility = function changeFiltersBlockVisibility(filterSection, element) {
    const filtersKey = "filtersVisible_" + filterSection;
    const filtersSavedValue = getCookie(filtersKey);

    element.style.display = filtersSavedValue === 'false' ? 'block' : 'none';
}