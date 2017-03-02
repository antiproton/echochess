/*
grid - display a table with a header

the columns are defined by the cols array passed to the constructor and
can't be modified once the grid is created.

the format for column definitions is:

[
	{
		Title: column header text,
		Width: col width in px,
		Value: field name or function that takes a row object
	},

	etc
]

if Value is a string, the value of that property of the current row is used.

if it's a function, the row is passed and the output is displayed.  the output
can be a string, an html element, or an array of such.
*/

function Grid(parent, cols) {
	Control.implement(this, parent);

	this.Columns = cols;
	this.Rows = [];
	this.RowClick = new Event(this);
	this.BeforeRowDraw = new Event(this);
	this.col_total_width = 0;

	this.SetupHtml();
}

Grid.prototype.SetupHtml = function() {
	this.header = div(this.Node);
	this.header_inner = div(this.header);

	Dom.Style(this.header, {
		color: "#FFFFF5",
		//fontWeight: "bold",
		fontSize: 11,
		borderTop: "1px solid #29520a",
		borderBottom: "1px solid #29520a",
		padding: "2px 2px 4px 6px",
		backgroundColor: "#497d22",
		cursor: "default",
		textShadow: "1px 1px rgba(0, 0, 0, 0.8)"
	});

	var col;
	var header_div;
	var total_width = 0;

	for(var i = 0; i < this.Columns.length; i++) {
		col = this.Columns[i];
		header_div = idiv(this.header_inner);

		Dom.Style(header_div, {
			width: col.Width
		});

		this.col_total_width += col.Width;

		header_div.innerHTML = col.Title;
	}

	Dom.Style(this.header_inner, {
		width: this.col_total_width
	});

	this.inner = div(this.Node);

	Dom.Style(this.inner, {
		//border: "1px solid #cbcbcb"
	});
}

Grid.prototype.Update = function(table) {
	this.Rows = table;
	this.update_grid();
}

Grid.prototype.update_grid = function() {
	var self = this;

	Dom.ClearNode(this.inner);

	var row, row_div, row_inner, col, col_div;

	for(var i = 0; i < this.Rows.length; i++) {
		row = this.Rows[i];
		row_div = div(this.inner);
		row_inner = div(row_div);

		Dom.Style(row_div, {
			padding: "4px 2px 4px 6px"
		});

		Dom.AddClass(row_div, "grid_row");

		this.BeforeRowDraw.Fire({
			Row: row,
			RowDiv: row_div
		});

		Dom.AddEventHandler(row_div, "click", (function(row) {
			return function(e) {
				self.RowClick.Fire({
					Row: row
				});
			};
		})(row));

		/*
		col definitions - if Value is a string, the value of that field is
		displayed directly.  If it's a function, the whole row is passed to
		it and its output is displayed.
		*/

		for(var j = 0; j < this.Columns.length; j++) {
			col = this.Columns[j];
			col_div = idiv(row_inner);

			Dom.Style(col_div, {
				fontSize: 11,
				width: col.Width
			});

			cell_contents = "";

			if(is_function(col.Value)) {
				var value = col.Value(row);

				if(!is_array(value)) {
					value = [value];
				}

				var item;

				for(var k = 0; k < value.length; k++) {
					item = value[k];

					if(is_string(item)) {
						col_div.innerHTML += item;
					}

					else {
						col_div.appendChild(item);
					}
				}
			}

			else if(is_string(col.Value)) {
				col_div.innerHTML = row[col.Value];
			}
		}

		Dom.Style(row_inner, {
			width: this.col_total_width
		});
	}
}