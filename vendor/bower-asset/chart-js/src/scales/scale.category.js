import Scale from '../core/core.scale';
import {isNullOrUndef, valueOrDefault, _limitValue} from '../helpers';

const addIfString = (labels, raw, index) => typeof raw === 'string'
  ? labels.push(raw) - 1
  : isNaN(raw) ? null : index;

function findOrAddLabel(labels, raw, index) {
  const first = labels.indexOf(raw);
  if (first === -1) {
    return addIfString(labels, raw, index);
  }
  const last = labels.lastIndexOf(raw);
  return first !== last ? index : first;
}

const validIndex = (index, max) => index === null ? null : _limitValue(Math.round(index), 0, max);

export default class CategoryScale extends Scale {

  constructor(cfg) {
    super(cfg);

    /** @type {number} */
    this._startValue = undefined;
    this._valueRange = 0;
  }

  parse(raw, index) {
    if (isNullOrUndef(raw)) {
      return null;
    }
    const labels = this.getLabels();
    index = isFinite(index) && labels[index] === raw ? index
      : findOrAddLabel(labels, raw, valueOrDefault(index, raw));
    return validIndex(index, labels.length - 1);
  }

  determineDataLimits() {
    const {minDefined, maxDefined} = this.getUserBounds();
    let {min, max} = this.getMinMax(true);

    if (this.options.bounds === 'ticks') {
      if (!minDefined) {
        min = 0;
      }
      if (!maxDefined) {
        max = this.getLabels().length - 1;
      }
    }

    this.min = min;
    this.max = max;
  }

  buildTicks() {
    const min = this.min;
    const max = this.max;
    const offset = this.options.offset;
    const ticks = [];
    let labels = this.getLabels();

    // If we are viewing some subset of labels, slice the original array
    labels = (min === 0 && max === labels.length - 1) ? labels : labels.slice(min, max + 1);

    this._valueRange = Math.max(labels.length - (offset ? 0 : 1), 1);
    this._startValue = this.min - (offset ? 0.5 : 0);

    for (let value = min; value <= max; value++) {
      ticks.push({value});
    }
    return ticks;
  }

  getLabelForValue(value) {
    const labels = this.getLabels();

    if (value >= 0 && value < labels.length) {
      return labels[value];
    }
    return value;
  }

  /**
	 * @protected
	 */
  configure() {
    super.configure();

    if (!this.isHorizontal()) {
      // For backward compatibility, vertical category scale reverse is inverted.
      this._reversePixels = !this._reversePixels;
    }
  }

  // Used to get data value locations. Value can either be an index or a numerical value
  getPixelForValue(value) {
    if (typeof value !== 'number') {
      value = this.parse(value);
    }

    return value === null ? NaN : this.getPixelForDecimal((value - this._startValue) / this._valueRange);
  }

  // Must override base implementation because it calls getPixelForValue
  // and category scale can have duplicate values
  getPixelForTick(index) {
    const ticks = this.ticks;
    if (index < 0 || index > ticks.length - 1) {
      return null;
    }
    return this.getPixelForValue(ticks[index].value);
  }

  getValueForPixel(pixel) {
    return Math.round(this._startValue + this.getDecimalForPixel(pixel) * this._valueRange);
  }

  getBasePixel() {
    return this.bottom;
  }
}

CategoryScale.id = 'category';

/**
 * @type {any}
 */
CategoryScale.defaults = {
  ticks: {
    callback: CategoryScale.prototype.getLabelForValue
  }
};
